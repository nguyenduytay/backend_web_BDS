<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PropertyImage; // Add this line
use App\Models\Contact; // You may need to add this and others too
use App\Models\Location;
use App\Models\Feature;
use App\Models\PropertyType;
use App\Models\User;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'properties';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'location_id',
        'property_type_id',
        'status',
        'price',
        'area',
        'bedrooms',
        'bathrooms',
        'floors',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'year_built',
        'contact_id',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    // Define relationships

    /**
     * Get the location that owns the property.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the contact that owns the property.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the property type associated with the property.
     */
    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id', 'id');
    }


    /**
     * The features that belong to the property.
     * This is a many-to-many relationship using a pivot table.
     */
    // public function features()
    // {
    //     return $this->belongsToMany(Feature::class, 'property_features');
    // }

    /**
     * Get the images for the property.
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Get the user who created the property.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the property.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'property_features', 'property_id', 'feature_id');
    }
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class, 'property_id')
            ->where('is_primary', 1)
            ->orderBy('updated_at', 'DESC');
    }
}
