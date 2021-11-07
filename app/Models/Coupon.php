<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Coupon extends Model
{
    use Translatable;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'coupon_products');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'coupon_categories');
    }
}