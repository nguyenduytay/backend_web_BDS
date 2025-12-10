<?php

namespace App\Services;

use App\Repositories\PropertyFeatureRepository\PropertyFeatureRepositoryInterface;
use Throwable;

class PropertyFeatureService extends BaseService
{
    protected $propertyFeatureRepository;

    public function __construct(PropertyFeatureRepositoryInterface $propertyFeatureRepository)
    {
        $this->propertyFeatureRepository = $propertyFeatureRepository;
    }

    public function getFeaturesByProperty($propertyId)
    {
        try {
            return $this->propertyFeatureRepository->getFeaturesByProperty($propertyId);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyFeatureService::getFeaturesByProperty');
            return null;
        }
    }

    public function addFeatureToProperty($propertyId, $data)
    {
        try {
            return $this->propertyFeatureRepository->addFeatureToProperty($propertyId, $data['feature_id']);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyFeatureService::addFeatureToProperty');
            return null;
        }
    }

    public function syncFeaturesToProperty($propertyId, $request)
    {
        try {
            return $this->propertyFeatureRepository->syncFeatures($propertyId, $request['feature_ids']);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyFeatureService::syncFeaturesToProperty');
            return null;
        }
    }

    public function removeFeatureFromProperty($propertyId, $featureId)
    {
        try {
            return $this->propertyFeatureRepository->removeFeatureFromProperty($propertyId, $featureId);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyFeatureService::removeFeatureFromProperty');
            return null;
        }
    }
}
