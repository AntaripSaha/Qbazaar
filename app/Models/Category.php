<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Category extends Model
{
    use Translatable;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    protected $fillable = ['icon', 'image', 'parent_id'];

    public function parent_category(){
        return $this->belongsTo('App\Models\Category','parent_id')->withDefault();
    }

    public function child_categories(){
        return $this->hasMany('App\Models\Category','parent_id');
    }

    public function products(){
        return $this->hasMany('App\Models\Product','category_id');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = mb_strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value)));
    }
	
}