<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\PropertyRepository\PropertyRepositoryInterface;
use Exception;

class PropertyService extends BaseService
{
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties($request)
    {
        return $this->execute(function () use ($request) {
            return $this->propertyRepository->allProperty($request);
        }, 'PropertyService::getAllProperties');
    }

    public function show($id)
    {
        return $this->execute(function () use ($id) {
            return $this->propertyRepository->find($id);
        }, 'PropertyService::show');
    }

    public function allByPropertyType($propertyTypeId, $request)
    {
        return $this->execute(function () use ($propertyTypeId, $request) {
            return $this->propertyRepository->allByPropertyType($propertyTypeId, $request);
        }, 'PropertyService::allByPropertyType');
    }

    public function allByLoaction($request)
    {
        return $this->execute(function () use ($request) {
            return $this->propertyRepository->allByLoaction($request);
        }, 'PropertyService::allByLoaction');
    }

    public function allByOutstand($request)
    {
        return $this->execute(function () use ($request) {
            return $this->propertyRepository->allByOutstand($request);
        }, 'PropertyService::allByOutstand');
    }

    public function create($request)
    {
        return $this->execute(function () use ($request) {
            return $this->propertyRepository->create($request->all());
        }, 'PropertyService::create');
    }

    public function update($id, Request $request)
    {
        return $this->execute(function () use ($id, $request) {
            return $this->propertyRepository->update($id, $request->all());
        }, 'PropertyService::update');
    }

    public function delete($id)
    {
        return $this->execute(function () use ($id) {
            return $this->propertyRepository->delete($id);
        }, 'PropertyService::delete');
    }

    public function restore($id)
    {
        return $this->execute(function () use ($id) {
            return $this->propertyRepository->restore($id);
        }, 'PropertyService::restore');
    }

    public function forceDelete($id)
    {
        return $this->execute(function () use ($id) {
            return $this->propertyRepository->forceDelete($id);
        }, 'PropertyService::forceDelete');
    }

    public function getPropertiesByUser($userId)
    {
        return $this->execute(function () use ($userId) {
            return $this->propertyRepository->getPropertiesByUser($userId);
        }, 'PropertyService::getPropertiesByUser');
    }
}
