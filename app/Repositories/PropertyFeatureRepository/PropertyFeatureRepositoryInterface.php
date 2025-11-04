<?php

namespace App\Repositories\PropertyFeatureRepository;

use App\Repositories\RepositoryInterface;

interface PropertyFeatureRepositoryInterface extends RepositoryInterface
{
    public function getFeaturesByProperty($propertyId);
    public function addFeatureToProperty($propertyId, $featureId);
    public function syncFeatures($propertyId, $featureIds);
    public function removeFeatureFromProperty($propertyId, $featureId);
}
