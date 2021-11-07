<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;

class CouponController extends Controller {

    public function getCoupon() {
        $code = request('code');
        $currenct_date = date('Y-m-d');
        
        $coupon = Coupon::where('is_active', 1)
            ->whereRaw("BINARY `code`= ?",[$code])
            ->where('start_date','<=',$currenct_date)
            ->where('end_date','>=',$currenct_date)
            ->with(['products', 'categories'])
            ->first();

        if (!$coupon) {
            return response()->json([], 404);
        }

        return new CouponResource($coupon);
    }

}
