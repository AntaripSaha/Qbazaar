<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Auth;

class OrderService {
    public function create($orderData) {
        return tap($this->store($orderData), function ($order) use ($orderData) {
            $this->storeOrderProducts($order, $orderData['cartItems']);
            $this->incrementCouponUsage($order);
            return $order;
        });
    }

    private function store($orderData) {
        $shipping_method  = 'Flat Rate';
        $shipping_cost    = $orderData['deliveryCharge'];
        $coupon_id        = $orderData['coupon_id'];
        $discount         = $orderData['discount'];
        $sud_total        = $orderData['sub_total'];
        $grand_total      = $orderData['grand_total'];
        $payment_method   = $orderData['deliveryDetails']->paymentMethod;
        $order_note       = $orderData['deliveryDetails']->deliveryTiming->order_instructions;
        $delivery_type    = $orderData['deliveryDetails']->deliveryTiming->delivery_type;
        $delivery_time    = $orderData['deliveryDetails']->deliveryTiming->delivery_date;
        $shipping_address = $orderData['deliveryDetails']->deliveryAddress->address;

        return Order::create([
            'customer_id'      => Auth::Guard('api')->user()->id,
            'customer_name'    => Auth::Guard('api')->user()->name,
            'customer_email'   => Auth::Guard('api')->user()->email,
            'customer_phone'   => Auth::Guard('api')->user()->phone,
            'shipping_address' => $shipping_address,
            'sub_total'        => $sud_total,
            'shipping_method'  => $shipping_method,
            'shipping_cost'    => $shipping_cost,
            'coupon_id'        => $coupon_id,
            'discount'         => $discount,
            'total'            => $grand_total,
            'currency'         => get_option('currency'),
            'payment_method'   => $payment_method,
            'payment_status'   => Order::PENDING,
            'delivery_status'  => Order::PENDING,
            'note'             => $order_note,
            'delivery_type'    => $delivery_type,
            'delivery_time'    => $delivery_type == 'preorder' ? $delivery_time : date('Y-m-d H:m:i', strtotime('+1 hour')),
        ]);
    }

    private function storeOrderProducts(Order $order, $cartItems) {
        foreach ($cartItems as $cartItem) {
            $order->storeProducts($cartItem);
        }
    }

    private function incrementCouponUsage(Order $order) {
        if ($order->coupon_id != null) {
            $coupon = Coupon::find($order->coupon_id);
            $coupon->increment('used');
        }
    }

}
