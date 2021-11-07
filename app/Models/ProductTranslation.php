<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_translations';
	
    protected $fillable = ['name', 'description'];
	
}