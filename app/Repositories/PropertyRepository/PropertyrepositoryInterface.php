<?php

namespace App\Repositories\PropertyRepository;

use App\Models\Property;
use App\Repositories\RepositoryInterface;

interface PropertyRepositoryInterface extends RepositoryInterface
{
    public function allProperty($request);
    public function allByPropertyType($propertyTypeId, $request);
    public function allByLoaction($request);
    public function allByOutstand($request);
    public function restore($id);
    public function forceDelete($id);
    public function getPropertiesByUser($userId);
}
