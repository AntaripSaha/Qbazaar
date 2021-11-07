<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    const CANCELED   = 'canceled';
    const COMPLETED  = 'completed';
    const PENDING    = 'pending';
    const PAID       = 'paid';
    const PROCESSING = 'processing';
    const REFUNDED   = 'refunded';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function products() {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function delivery_boy() {
        return $this->belongsTo('App\Models\User', 'delivery_boy_id')->withDefault();
    }

    public function coupon() {
        return $this->belongsTo('App\Models\Coupon')->withDefault();
    }

    public function transaction() {
        return $this->hasOne('App\Models\Transaction', 'order_id')->withDefault();
    }

    public function storeProducts($cartItem) {
        $this->products()->create([
            'product_id' => $cartItem['id'],
            'unit_price' => $cartItem['unit_price'],
            'qty'        => $cartItem['qnty'],
            'line_total' => $cartItem['total_price'],
        ]);

    }

    public function getCreatedAtAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function getDeliveryTimeAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function getPaymentStatus() {
        if ($this->payment_status == $this::CANCELED) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        } else if ($this->payment_status == $this::COMPLETED) {
            return '<span class="badge badge-success">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        } else if ($this->payment_status == $this::PENDING) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        } else if ($this->payment_status == $this::PROCESSING) {
            return '<span class="badge badge-info">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        } else if ($this->payment_status == $this::REFUNDED) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        } else if ($this->payment_status == $this::PAID) {
            return '<span class="badge badge-success">' . ucwords(str_replace("_", " ", $this->payment_status)) . '</span>';
        }
    }

    public function getDeliveryStatus() {
        if ($this->delivery_status == $this::CANCELED) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->delivery_status)) . '</span>';
        } else if ($this->delivery_status == $this::COMPLETED) {
            return '<span class="badge badge-success">' . ucwords(str_replace("_", " ", $this->delivery_status)) . '</span>';
        } else if ($this->delivery_status == $this::PENDING) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->delivery_status)) . '</span>';
        } else if ($this->delivery_status == $this::PROCESSING) {
            return '<span class="badge badge-info">' . ucwords(str_replace("_", " ", $this->delivery_status)) . '</span>';
        } else if ($this->delivery_status == $this::REFUNDED) {
            return '<span class="badge badge-danger">' . ucwords(str_replace("_", " ", $this->delivery_status)) . '</span>';
        }
    }

}