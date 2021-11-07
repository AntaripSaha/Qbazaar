<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.order.list');
    }

    public function get_table_data()
    {

        $orders = Order::select('orders.*')
            ->orderBy("orders.id", "desc");

        return Datatables::eloquent($orders)
            ->editColumn('payment_status', function ($order) {
                return $order->getPaymentStatus();
            })
            ->editColumn('delivery_status', function ($order) {
                return $order->getDeliveryStatus();
            })
            ->editColumn('total', function ($order) {
                return decimalPlace($order->total, $order->currency);
            })
            ->addColumn('action', function ($order) {
                if ($order->delivery_status != 'completed') {
                    return '<div class="text-center">
							<div class="dropdown">'
                        . '<button class="btn btn-light btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . _lang('Action')
                        . '</button>'
                        . '<div class="dropdown-menu dropdown-menu-right">'
                        . '<a class="dropdown-item" href="' . action('OrderController@show', $order->id) . '"><i class="icofont-eye-alt"></i> ' . _lang('Order Details') . '</a>'
                        . '<a class="dropdown-item ajax-modal" href="' . action('OrderController@assign_delivery_boy', $order->id) . '" data-title="' . _lang('Assign Delivery Boy') . '"><i class="icofont-delivery-time"></i> ' . _lang('Assign Delivery Boy') . '</a>'
                        . '<form action="' . action('OrderController@destroy', $order['id']) . '" method="post">'
                        . csrf_field()
                        . '<input name="_method" type="hidden" value="DELETE">'
                        . '<button class="button-link btn-remove" type="submit"><i class="icofont-ui-delete"></i> ' . _lang('Delete') . '</button>'
                        . '</form>'
                        . '</div>'
                        . '</div>'
                        . '</div>';
                }
                return '<div class="text-center">
							<div class="dropdown">'
                    . '<button class="btn btn-light btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . _lang('Action')
                    . '</button>'
                    . '<div class="dropdown-menu dropdown-menu-right">'
                    . '<a class="dropdown-item" href="' . action('OrderController@show', $order->id) . '"><i class="icofont-eye-alt"></i> ' . _lang('Order Details') . '</a>'
                    . '<form action="' . action('OrderController@destroy', $order['id']) . '" method="post">'
                    . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="button-link btn-remove" type="submit"><i class="icofont-ui-delete"></i> ' . _lang('Delete') . '</button>'
                    . '</form>'
                    . '</div>'
                    . '</div>'
                    . '</div>';
            })
            ->setRowId(function ($order) {
                return "row_" . $order->id;
            })
            ->rawColumns(['action', 'payment_status', 'delivery_status', 'total'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$request->ajax()) {
            return view('backend.order.view', compact('order', 'id'));
        } else {
            return view('backend.order.modal.view', compact('order', 'id'));
        }
    }

    /**
     * Assign delivery boy
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_delivery_boy(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            $order = Order::find($id);

            if (!$request->ajax()) {
                return back();
            } else {
                return view('backend.order.modal.assign_delivery_boy', compact('order', 'id'));
            }
        } else {

            $validator = Validator::make($request->all(), [
                'delivery_boy_id' => 'required',
            ], [
                'delivery_boy_id.required_if' => _lang('Delivery boy is required !'),
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
                } else {
                    return back()->withErrors($validator)
                        ->withInput();
                }
            }

            $order                    = Order::find($id);
            $previous_delivery_boy_id = $order->delivery_boy_id;
            $order->delivery_boy_id   = $request->delivery_boy_id;
            $order->delivery_status   = $request->delivery_status;
            $order->save();

            if ($previous_delivery_boy_id != $order->delivery_boy_id) {
                return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Order Updated Successfully'), 'data' => $order, 'table' => '#orders_table']);
            }

            return response()->json(['result' => 'success', 'action' => 'not_update', 'message' => _lang('Order Updated Successfully'), 'data' => $order, 'table' => '#orders_table']);
        }
    }

    public function trigger_assign_delivery_boy($orderId)
    {
        $order = Order::find($orderId);
        if ($order->delivery_boy_id != null) {
            //Trigger Order Update Event
            event(new \App\Events\AssignDeliveryBoy($order));
            event(new \App\Events\OrderUpdated($order));
        }
    }

    /**
     * Update Order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'delivery_status' => 'required',
            // 'payment_status'  => 'required',
            //'transaction_id'  => 'required_if:payment_status,paid',
            'payment_method'  => 'required_if:payment_status,paid',
        ], [
            'transaction_id.required_if' => _lang('Transaction ID is required !'),
            'payment_method.required_if' => _lang('Payment Method is required !'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        $order                    = Order::find($id);
        $previous_payment_status  = $order->payment_status;
        $previous_delivery_status = $order->delivery_status;
        $order->delivery_status = $request->delivery_status;
        if ($request->delivery_status == 'completed') {
            $order->payment_status  = 'paid';
        } elseif ($request->delivery_status == 'processing' || $request->delivery_status == 'pending') {
            $order->payment_status  = 'pending';
        }
        //   $order->payment_status  = $request->payment_status;
        $order->payment_method  = $request->payment_method;
        $order->save();

        //Update Transaction
        // if ($request->payment_status == 'paid' && $previous_payment_status != 'paid') {
        //     $transaction                 = new Transaction();
        //     $transaction->order_id       = $order->id;
        //     $transaction->transaction_id = $request->transaction_id;
        //     $transaction->payment_method = $request->payment_method;
        //     $transaction->amount         = $order->total;
        //     $transaction->save();
        // }

        if ($order->delivery_status != $previous_delivery_status) {
            //Trigger Order Status Changed Event
            event(new \App\Events\OrderUpdated($order));
        }

        DB::commit();

        return back()->with('success', _lang('Order Updated Sucessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', _lang('Deleted Successfully'));
    }
}
