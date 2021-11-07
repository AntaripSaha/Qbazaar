<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponTranslation extends Model
{
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon_translations';
	
    protected $fillable = ['name'];
	
}