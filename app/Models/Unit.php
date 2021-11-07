<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Unit extends Model
{
    use Translatable;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units';
}