<?php

namespace App\Http\Controllers;
use App\Models\Order;

class DashboardController extends Controller {
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'user') {
            $data                = array();
            $data['total_sales'] = Order::selectraw('IFNULL(SUM(orders.total), 0) as amount')
                ->first()->amount;

            $data['current_day_sales'] = Order::selectraw('IFNULL(SUM(orders.total), 0) as amount')
                ->whereRaw("date(orders.created_at)='" . date('Y-m-d') . "'")
                ->first()->amount;

            $data['pending_orders']    = Order::where('delivery_status', 'pending')->count();
            $data['processing_orders'] = Order::where('delivery_status', 'processing')->count();

            $data['recent_orders'] = Order::orderBy('id', 'desc')->limit(10)->get();

            return view('backend.dashboard', $data);
        }
        return redirect('/account');
    }

    public function total_sales_widget() {
        // Use for Permission Only
        return redirect('/admin/dashboard');
    }

    public function current_day_sales_widget() {
        // Use for Permission Only
        return redirect('/admin/dashboard');
    }

    public function pending_payments_widget() {
        // Use for Permission Only
        return redirect('/admin/dashboard');
    }

    public function order_processing_widget() {
        // Use for Permission Only
        return redirect('/admin/dashboard');
    }

    public function month_wise_sales_widget() {
        $date   = date("Y-m-d");
        $orders = Order::selectRaw("DATE_FORMAT(created_at,'%b') as month, orders.total as amount, count(id) as order_count")
            ->whereRaw("YEAR(created_at) = YEAR('$date')")
            ->groupByRaw("MONTHNAME(created_at)")
            ->get();

        $result = array();
        foreach ($orders as $order) {
            $result[$order->month] = $order;
        }

        echo json_encode($result);
    }

    public function recent_order_widget() {
        // Use for Permission Only
        return redirect('/admin/dashboard');
    }

}
