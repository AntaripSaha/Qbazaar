<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    public function getCreatedAtAttribute($value)
    {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id')->withDefault();
    }

}