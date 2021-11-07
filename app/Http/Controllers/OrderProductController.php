<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\UnitTranslation;
use Carbon\Carbon;
use PDF;

class OrderProductController extends Controller
{
    //-----------------All Products View Start--------------------//

    public function index()


    {
        $order_products = OrderProduct::whereDate('created_at', Carbon::today())->select('product_id', 'created_at')->groupBy('product_id')->get();
        $order_products_date = OrderProduct::whereDate('created_at', Carbon::today())->select('created_at')->first();
        $total_products = [];
        $product_names = [];
        $unit_translation = [];
        foreach ($order_products as $order_product) {

            $total_products[] = OrderProduct::whereDate('created_at', Carbon::today())
                ->where('product_id', $order_product->product_id)->sum('qty');
            array_push($total_products);

            $product_names[] = ProductTranslation::where('product_id', $order_product->product_id)
                ->select('name')->first();
            array_push($product_names);

            $unit_id = Product::where('id', $order_product->product_id)->pluck('unit_id')->first();
            $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
            array_push($unit_translation);
        }
        return view('backend.order_product.order_product', compact('total_products', 'product_names', 'order_products', 'unit_translation', 'order_products_date'));
    }

    //-----------------All Products View End--------------------//

    //-----------------All Products Search Start--------------------//

    public function search(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        $results = OrderProduct::whereBetween('created_at', [$from, $to])->groupBy('product_id')->select('product_id')->get();
        $from_data = OrderProduct::whereDate('created_at', $from)->groupBy('product_id')->select('product_id')->get();
        $to_data = OrderProduct::whereDate('created_at', $to)->groupBy('product_id')->select('product_id')->get();
        $total_products = [];
        $product_names = [];
        $unit_translation = [];

        if ($from !== Null && $to !== Null) {

            foreach ($results as $result) {

                $total_products[] = OrderProduct::where('product_id', $result->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $result->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $result->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }

            return view('backend.order_product.search', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
        } elseif ($from == Null && $to == Null) {

            return redirect()->back()->with('error', 'Select Date First');
        } elseif ($from !== Null) {
            foreach ($from_data as $from_data) {

                $total_products[] = OrderProduct::where('product_id', $from_data->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $from_data->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $from_data->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }
            return view('backend.order_product.search', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
        } elseif ($to !== Null) {

            foreach ($to_data as $to_data) {

                $total_products[] = OrderProduct::where('product_id', $to_data->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $to_data->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $to_data->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }

            return view('backend.order_product.search', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
        }
    }
    //-----------------All Products Search End--------------------//

    //-----------------All Products Download Start--------------------//

    public function download()
    {
        $order_products = OrderProduct::whereDate('created_at', Carbon::today())->select('product_id', 'created_at')->groupBy('product_id')->get();
        $order_products_date = OrderProduct::whereDate('created_at', Carbon::today())->select('created_at')->first();
        $total_products = [];
        $product_names = [];
        $unit_translation = [];
        foreach ($order_products as $order_product) {

            $total_products[] = OrderProduct::whereDate('created_at', Carbon::today())
                ->where('product_id', $order_product->product_id)->sum('qty');
            array_push($total_products);

            $product_names[] = ProductTranslation::where('product_id', $order_product->product_id)
                ->select('name')->first();
            array_push($product_names);

            $unit_id = Product::where('id', $order_product->product_id)->pluck('unit_id')->first();
            $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
            array_push($unit_translation);
        }


        $pdf = PDF::loadview('backend.order_product.all_pdf', compact('total_products', 'product_names', 'order_products', 'unit_translation', 'order_products_date'));
        $pdf->setPaper('A4', 'potrait');
        $name = "products-list.pdf";
        return $pdf->stream($name, array("Attachment" => false));
    }

    //-----------------All Products Download End--------------------//

    //-----------------All Products Search Wise Download Start--------------------//

    public function search_download(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $results = OrderProduct::whereBetween('created_at', [$from, $to])->groupBy('product_id')->select('product_id')->get();
        $from_data = OrderProduct::whereDate('created_at', $from)->groupBy('product_id')->select('product_id')->get();
        $to_data = OrderProduct::whereDate('created_at', $to)->groupBy('product_id')->select('product_id')->get();
        $total_products = [];
        $product_names = [];
        $unit_translation = [];

        if ($from !== Null && $to !== Null) {

            foreach ($results as $result) {

                $total_products[] = OrderProduct::where('product_id', $result->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $result->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $result->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }

            $pdf = PDF::loadview('backend.order_product.search_pdf', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
            $pdf->setPaper('A4', 'potrait');
            $name = "products-list.pdf";
            return $pdf->stream($name, array("Attachment" => false));
        } elseif ($from !== Null) {
            foreach ($from_data as $from_data) {

                $total_products[] = OrderProduct::where('product_id', $from_data->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $from_data->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $from_data->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }

            $pdf = PDF::loadview('backend.order_product.search_pdf', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
            $pdf->setPaper('A4', 'potrait');
            $name = "products-list.pdf";
            return $pdf->stream($name, array("Attachment" => false));
        } elseif ($to !== Null) {

            foreach ($to_data as $to_data) {

                $total_products[] = OrderProduct::where('product_id', $to_data->product_id)
                    ->sum('qty');
                array_push($total_products);

                $product_names[] = ProductTranslation::where('product_id', $to_data->product_id)
                    ->select('name')->first();
                array_push($product_names);

                $unit_id = Product::where('id', $to_data->product_id)->pluck('unit_id')->first();
                $unit_translation[] = UnitTranslation::where('id', $unit_id)->select('name')->first();
                array_push($unit_translation);
            }


            $pdf = PDF::loadview('backend.order_product.search_pdf', compact('total_products', 'product_names', 'unit_translation', 'from', 'to'));
            $pdf->setPaper('A4', 'potrait');
            $name = "products-list.pdf";
            return $pdf->stream($name, array("Attachment" => false));
        }
    }

    //-----------------All Products Search Wise Download End--------------------//

}
