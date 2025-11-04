<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';
    protected $fillable = ['name', 'icon']; // các field được phép insert/update

    public function properties()
{
    return $this->belongsToMany(\App\Models\Property::class, 'property_features', 'feature_id', 'property_id');
}



}
