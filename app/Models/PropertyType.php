<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_types';

    protected $fillable = [
        'id',
        'type',
        'name'
    ];

    public $timestamps = true;
}
