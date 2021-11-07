<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Validator;
use DB;

class CouponController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $coupons = Coupon::all()->sortByDesc("id");
        return view('backend.coupon.list', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.coupon.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'code'       => 'required',
            'value'      => 'required',
            'is_percent' => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('coupons.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        $coupon             = new Coupon();
        $coupon->code       = $request->input('code');
        $coupon->value      = $request->input('value');
        $coupon->is_percent = $request->input('is_percent');
        $coupon->is_active  = isset($request->is_active) ? 1 : 0;
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date   = $request->input('end_date');

        $coupon->save();

        //Store Products
        if(isset($request->products)){
            $coupon->products()->attach($request->products);
        }

        //Store Category
        if(isset($request->categories)){
            $coupon->categories()->attach($request->categories);
        }

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('coupons.index')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $coupon, 'table' => '#coupons_table']);
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $coupon = Coupon::find($id);
        if (!$request->ajax()) {
            return back();
        } else {
            return view('backend.coupon.modal.edit', compact('coupon', 'id'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'code'       => 'required',
            'value'      => 'required',
            'is_percent' => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('coupons.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        $coupon             = Coupon::find($id);
        $coupon->code       = $request->input('code');
        $coupon->value      = $request->input('value');
        $coupon->is_percent = $request->input('is_percent');
        $coupon->is_active  = isset($request->is_active) ? 1 : 0;
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date   = $request->input('end_date');

        $coupon->save();

        //Store Products
        if(isset($request->products)){
            $coupon->products()->sync($request->products);
        }else{
            $coupon->products()->detach();
        }

        //Store Category
        if(isset($request->categories)){
            $coupon->categories()->sync($request->categories);
        }else{
            $coupon->categories()->detach();
        }

        DB::commit();

        if (!$request->ajax()) {
            return redirect()->route('coupons.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $coupon, 'table' => '#coupons_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', _lang('Deleted Successfully'));
    }
}