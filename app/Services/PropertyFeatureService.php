<?php

namespace App\Services;

use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepositoryInterface;
use Exception;

class PropertyFeatureService
{
    protected $propertyFeatureRepository;

    public function __construct(PropertyFeatureRepositoryInterface $propertyFeatureRepository)
    {
        $this->propertyFeatureRepository = $propertyFeatureRepository;
    }

    public function getFeaturesByProperty($propertyId)
    {
        try {
            $data = $this->propertyFeatureRepository->getFeaturesByProperty($propertyId);
            return $data;
        } catch (Exception $e) {
            return null;
        }
    }

    public function addFeatureToProperty($propertyId, $data)
    {
        try {
            return $this->propertyFeatureRepository->addFeatureToProperty($propertyId, $data['feature_id']);
        } catch (Exception $e) {
            return null;
        }
    }

    public function syncFeaturesToProperty($propertyId, $request)
    {
        try {
            return $this->propertyFeatureRepository->syncFeatures($propertyId, $request['feature_ids']);
        } catch (Exception $e) {
            return null;
        }
    }

    public function removeFeatureFromProperty($propertyId, $featureId)
    {
        try {
            return $this->propertyFeatureRepository->removeFeatureFromProperty($propertyId, $featureId);
        } catch (Exception $e) {
            return null;
        }
    }
}
