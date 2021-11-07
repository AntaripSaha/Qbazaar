<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Product extends Model
{
    use Translatable;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id')->withDefault();
    }

    public function unit(){
        return $this->belongsTo('App\Models\Unit','unit_id')->withDefault();
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = mb_strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value)));
    }
}