<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitTranslation extends Model
{
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unit_translations';
	
    protected $fillable = ['name', 'short_name'];
	
}