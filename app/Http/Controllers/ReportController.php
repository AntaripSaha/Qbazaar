<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use DB;

class ReportController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
    }
  
    /**
     * Show Customer Order Report.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function order_report(Request $request)
    {
		if($request->isMethod('get')){
		    return view('backend.reports.order_report');	
		}else if($request->isMethod('post')){
			$data = array();
			$date1 = $request->date1;
		    $date2 = $request->date2;
		    $payment_status = isset($request->payment_status) ? $request->payment_status : '';
		    $customer_email = isset($request->customer_email) ? $request->customer_email : '';
			
			$data['report_data'] = Order::select('customer_id', 'customer_name', 'customer_email')
											   ->when($payment_status, function ($query, $payment_status) {
												   return $query->where('payment_status', $payment_status);
											   })
											   ->when($customer_email, function ($query, $customer_email) {
												   return $query->where('customer_email', $customer_email);
											   })
											   ->selectRaw('COUNT(DISTINCT(orders.id)) as total_orders')
											   ->join('order_products', 'orders.id', '=', 'order_products.order_id')
											   ->selectRaw('SUM(order_products.qty) as total_products')
											   ->selectRaw('SUM(orders.total) * COUNT(DISTINCT orders.id) / COUNT(*) as total')
											   ->whereRaw("date(orders.created_at) >= '$date1' AND date(orders.created_at) <= '$date2'")
											   ->groupBy([
													'customer_id',
													'customer_name',
													'customer_email',
												])->get();

		   
			$data['date1'] = $request->date1;
		    $data['date2'] = $request->date2;
		    $data['payment_status'] = $request->payment_status;
		    $data['customer_email'] = $request->customer_email;
			return view('backend.reports.order_report',$data);
		}
        
    }
	
	
	/**
     * Show Sales Report.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sales_report(Request $request)
    {
		if($request->isMethod('get')){
		    return view('backend.reports.sales_report');	
		}else if($request->isMethod('post')){
			$data = array();
			$date1 = $request->date1;
		    $date2 = $request->date2;
		    $payment_status = isset($request->payment_status) ? $request->payment_status : '';
			
			$data['report_data'] = Order::select('customer_id', 'customer_name', 'customer_email', 'orders.created_at')
											   ->when($payment_status, function ($query, $payment_status) {
												   return $query->where('payment_status', $payment_status);
											   })
											   ->selectRaw('COUNT(DISTINCT(orders.id)) as total_orders')
											   ->join('order_products', 'orders.id', '=', 'order_products.order_id')
											   ->selectRaw('SUM(order_products.qty) as total_products')
											   ->selectRaw('SUM(orders.sub_total) * COUNT(DISTINCT orders.id) / COUNT(*) as sub_total')
											   ->selectRaw('IFNULL(SUM(orders.shipping_cost), 0)  * COUNT(DISTINCT orders.id) / COUNT(*) as shipping_cost')
											   ->selectRaw('IFNULL(SUM(orders.discount), 0) * COUNT(DISTINCT orders.id) / COUNT(*) as discount')
											   ->selectRaw('SUM(orders.total) * COUNT(DISTINCT orders.id) / COUNT(*) as total')
											   ->whereRaw("date(orders.created_at) >= '$date1' AND date(orders.created_at) <= '$date2'")
											   ->groupBy('orders.created_at')
											   ->get();
		   
		   
			$data['date1'] = $request->date1;
		    $data['date2'] = $request->date2;
		    $data['payment_status'] = $request->payment_status;
			return view('backend.reports.sales_report',$data);
		}
        
    }
	
}
