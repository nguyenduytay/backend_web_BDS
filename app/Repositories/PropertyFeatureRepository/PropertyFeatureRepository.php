<?php

namespace App\Repositories\PropertyFeatureRepository;

use App\Models\Property;
use App\Models\PropertyFeature;
use App\Repositories\BaseRepository;

class PropertyFeatureRepository extends BaseRepository implements PropertyFeatureRepositoryInterface
{
    public function getModel()
    {
        return Property::class;
    }
    public function getFeaturesByProperty($propertyId)
    {
        $property = $this->model::with('features')->findOrFail($propertyId);
        return $property->features;
    }

    public function addFeatureToProperty($propertyId, $featureId)
    {
        // Tìm property theo ID, nếu không có thì throw 404
        $property = $this->model::findOrFail($propertyId);

        // Gắn (attach) featureId vào property thông qua quan hệ many-to-many
        $property->features()->attach($featureId);

        // Trả về danh sách features hiện có của property
        return $property->features()->syncWithoutDetaching([$featureId]);
    }



    public function syncFeatures($propertyId, $featureIds)
    {
        $property = $this->model::findOrFail($propertyId);

        // sync sẽ thay thế toàn bộ feature hiện có bằng danh sách mới
        $property->features()->sync($featureIds);

        // Trả về danh sách features sau khi sync
        return $property->features;
    }


    public function removeFeatureFromProperty($propertyId, $featureId)
    {
        // Tìm property theo ID, nếu không có sẽ throw 404
        $property = Property::findOrFail($propertyId);

        // Xóa featureId ra khỏi bảng trung gian property_feature
        $property->features()->detach($featureId);

        // Có thể return lại danh sách features còn lại nếu muốn
        return $property->features;
    }
}
