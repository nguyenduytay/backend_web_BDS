<?php

namespace App\Services;

use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepositoryInterface;

class PropertyFeatureService extends BaseService
{
    protected $propertyFeatureRepository;

    public function __construct(PropertyFeatureRepositoryInterface $propertyFeatureRepository)
    {
        $this->propertyFeatureRepository = $propertyFeatureRepository;
    }

    public function getFeaturesByProperty($propertyId)
    {
        return $this->execute(function () use ($propertyId) {
            return $this->propertyFeatureRepository->getFeaturesByProperty($propertyId);
        }, 'PropertyFeatureService::getFeaturesByProperty');
    }

    public function addFeatureToProperty($propertyId, $data)
    {
        return $this->execute(function () use ($propertyId, $data) {
            return $this->propertyFeatureRepository->addFeatureToProperty($propertyId, $data['feature_id']);
        }, 'PropertyFeatureService::addFeatureToProperty');
    }

    public function syncFeaturesToProperty($propertyId, $request)
    {
        return $this->execute(function () use ($propertyId, $request) {
            return $this->propertyFeatureRepository->syncFeatures($propertyId, $request['feature_ids']);
        }, 'PropertyFeatureService::syncFeaturesToProperty');
    }

    public function removeFeatureFromProperty($propertyId, $featureId)
    {
        return $this->execute(function () use ($propertyId, $featureId) {
            return $this->propertyFeatureRepository->removeFeatureFromProperty($propertyId, $featureId);
        }, 'PropertyFeatureService::removeFeatureFromProperty');
    }
}
