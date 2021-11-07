<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\OrderService;
use Auth;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Stripe;

class OrderController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    public function index() {
        $order = Order::with('products')
            ->where('customer_id', Auth::Guard('api')->user()->id)
            ->orderBy('id', 'desc');
        return OrderResource::collection($order->paginate(5));
    }

    public function assigned_orders() {
        $order = Order::with('products')
            ->where('delivery_status', 'processing')
            ->where('delivery_boy_id', Auth::Guard('api')->user()->id)
            ->orderBy('id', 'desc');
        return OrderResource::collection($order->paginate(5));
    }

    public function delivered_orders() {
        $order = Order::with('products')
            ->where('delivery_status', 'completed')
            ->where('delivery_boy_id', Auth::Guard('api')->user()->id)
            ->orderBy('id', 'desc');
        return OrderResource::collection($order->paginate(5));
    }

    public function place_order(OrderService $orderService) {
        $orderData       = json_decode(file_get_contents('php://input'));
        $cartItems       = $orderData->cartItems;
        $couponCode      = $orderData->couponCode;
        $deliveryDetails = $orderData->deliveryDetails;

        $freedeliveryAmount = get_option('free_delivery_amount', 0);

        $productIds = array();
        $finalItems = array();
        foreach ($cartItems as $cartItem) {
            array_push($productIds, $cartItem->id);
        }
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($cartItems as $cartItem) {
            $product = $products->find($cartItem->id);
            if ($product) {
                $product_price = $product->special_price > 0 ? $product->special_price : $product->price;
                $total_price   = $product_price * $cartItem->qnty;
                array_push($finalItems, array('id' => $cartItem->id, 'qnty' => $cartItem->qnty, 'category_id' => $product->category_id, 'unit_price' => $product_price, 'total_price' => $total_price));
            }
        }

        $sub_total = array_reduce($finalItems, function ($total, $item) {
            return $total + $item['total_price'];
        });

        //Validate Coupon
        $discount  = 0;
        $coupon_id = null;
        if ($couponCode != null) {
            $coupon    = $this->validateCoupon($couponCode, $finalItems);
            $discount  = $coupon['discount'];
            $coupon_id = $coupon['coupon']->id;
        }

        if ($freedeliveryAmount > 0 && $sub_total > $freedeliveryAmount) {
            $deliveryCharge = 0;
        } else {
            $deliveryCharge = (float) get_option('delivery_charge', 10);
        }

        $grand_total = ($sub_total + $deliveryCharge) - $discount;

        $orderData = [
            'cartItems'       => $finalItems,
            'sub_total'       => $sub_total,
            'deliveryCharge'  => $deliveryCharge,
            'coupon_id'       => $coupon_id,
            'discount'        => $discount,
            'grand_total'     => $grand_total,
            'deliveryDetails' => $deliveryDetails,
        ];

        $order = $orderService->create($orderData);

        if ($order->payment_method == 'cash_on_delivery') {
            //Trigger Order Palced Event
            event(new \App\Events\OrderPlaced($order));
        }

        return new OrderResource($order);
    }

    public function show($id) {
        $order = Order::with('products')
            ->where('id', $id)
            ->where('customer_id', Auth::Guard('api')->user()->id)
            ->orWhere('delivery_boy_id', Auth::Guard('api')->user()->id)
            ->first();
        return new OrderResource($order);
    }

    public function make_payment($id) {
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);

        $paymentData = json_decode(file_get_contents('php://input'));

        //PayPal Payment Method
        if ($paymentData->paymentMethod == 'PayPal') {
            $orderID = $paymentData->orderData->orderID;
            $order   = Order::where('id', $paymentData->id)
                ->where('customer_id', Auth::Guard('api')->user()->id)
                ->first();

            $clientId     = get_option('paypal_client_id');
            $clientSecret = get_option('paypal_secret');

            if (get_option('paypal_mode') != 'production') {
                $environment = new SandboxEnvironment($clientId, $clientSecret);
            } else {
                $environment = new ProductionEnvironment($clientId, $clientSecret);
            }

            $client   = new PayPalHttpClient($environment);
            $response = $client->execute(new OrdersGetRequest($orderID));

            $paidTotal = $response->result->purchase_units[0]->amount->value;

            if ($paidTotal >= $order->total && $response->result->status == 'COMPLETED') {
                //Create Transaction
                $transaction                 = new Transaction();
                $transaction->order_id       = $order->id;
                $transaction->transaction_id = $response->result->id;
                $transaction->payment_method = 'PayPal';
                $transaction->amount         = $paidTotal;

                $transaction->save();

                $order->payment_status = Order::PAID;
                $order->save();
            }

            //Trigger Order Palced Event
            event(new \App\Events\OrderPlaced($order));

            return new OrderResource($order);
        }

        if ($paymentData->paymentMethod == 'credit_card') {
            $order = Order::where('id', $paymentData->id)
                ->where('customer_id', Auth::Guard('api')->user()->id)
                ->first();

            Stripe\Stripe::setApiKey(get_option('stripe_secret_key'));
            $charge = Stripe\Charge::create([
                "amount"      => $order->total * 100,
                "currency"    => get_option('currency'),
                "source"      => $paymentData->token,
                "description" => _lang('E-Commerce Purchase'),
            ]);

            // Retrieve Charge Details
            if ($charge->amount_refunded == 0 && $charge->failure_code == null && $charge->paid == true && $charge->captured == true) {

                $amount = $charge->amount / 100;

                if ($amount >= $order->total) {
                    //Create Transaction
                    $transaction                 = new Transaction();
                    $transaction->order_id       = $order->id;
                    $transaction->transaction_id = $charge->id;
                    $transaction->payment_method = 'Stripe';
                    $transaction->amount         = $amount;

                    $transaction->save();

                    $order->payment_status = Order::PAID;
                    $order->save();
                }

                //Trigger Order Palced Event
                event(new \App\Events\OrderPlaced($order));

                return new OrderResource($order);
            }
        }
    }

    public function mark_as_delivered($orderId) {
        $order = Order::where('id', $orderId)
            ->where('delivery_boy_id', Auth::Guard('api')->user()->id)
            ->first();

        if ($order) {
            $order->delivery_status = Order::COMPLETED;
            $order->payment_status  = Order::PAID;
            $order->save();

            //Trigger Order Palced Event
            event(new \App\Events\OrderUpdated($order));
            return new OrderResource($order);
        }

    }

    private function validateCoupon($code, $products) {
        $currenct_date = date('Y-m-d');

        $coupon = Coupon::where('is_active', 1)
            ->whereRaw("BINARY `code`= ?", [$code])
            ->where('start_date', '<=', $currenct_date)
            ->where('end_date', '>=', $currenct_date)
            ->with(['products', 'categories'])
            ->first();

        if (!$coupon) {
            return 0;
        }

        $couponProducts = array();

        if ($coupon->categories->count() > 0) {
            foreach ($coupon->categories as $category) {
                $product = $products->firstWhere('category_id', $category->id);
                if ($product) {
                    array_push($couponProducts, $product);
                }
            }

        } else if ($coupon->products->count() > 0) {
            foreach ($coupon->products as $p) {
                $product = $products->find($p->id);
                if ($product) {
                    array_push($couponProducts, $product);
                }
            }
        } else {
            $couponProducts = $products;
        }

        $coupon_amount = array_reduce($couponProducts, function ($total, $item) {
            return $total + $item['total_price'];
        });

        //Apply Coupon
        $discount = $coupon->is_percent == 1 ? ($coupon->value / 100) * $coupon_amount : $coupon->value;
        return array('coupon' => $coupon, 'discount' => $discount);
    }

}
