<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_translations';
	
    protected $fillable = ['name', 'description'];
	
}