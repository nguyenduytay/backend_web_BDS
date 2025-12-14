<?php

namespace App\Repositories\PropertyImageRepository;

use App\Models\PropertyImage;
use App\Repositories\BaseRepository;

class PropertyImageRepository extends BaseRepository implements PropertyImageRepositoryInterface
{
    public function getModel()
    {
        return PropertyImage::class;
    }
    public function allImages($propertyId)
    {
        return $this->model::where('property_id', $propertyId)->get();
    }
    public function detailPropertyImage($propertyId, $imageId)
    {
        return $this->model::where('property_id', $propertyId)->where('id', $imageId)->first();
    }
    public function getAllHomeAvatars()
    {
        $images = PropertyImage::query()->select(
            'property_images.id',
            'property_images.image_path',
            'properties.id as property_id',
            'properties.title',
            'properties.address',
            'properties.price',
            'properties.created_at'
        )
            ->join('properties', 'properties.id', '=', 'property_images.property_id')
            ->where('property_images.is_primary', 1)
            ->orderByDesc('properties.created_at')
            ->limit(10)
            ->get();

        return $images;
    }
}
