<?php

namespace App\Services;

use App\Repositories\PropertyRepository\PropertyRepository;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Repositories\PropertyRepository\PropertyRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class PropertyService
{
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties($request)
    {
        try {
            $data = $this->propertyRepository->allProperty($request);
            return $data;
        } catch (Exception $e) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $property = $this->propertyRepository->find($id);
            return $property;
        } catch (Exception $e) {
            return null;
        }
    }
    public function allByPropertyType($propertyTypeId, $request)
    {
        try {
            return $this->propertyRepository->allByPropertyType($propertyTypeId, $request);
        } catch (Exception $e) {
            Log::error('allByPropertyType service error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    public function allByLoaction($request)
    {
        try {
            return $this->propertyRepository->allByLoaction($request);
        } catch (Exception $e) {
            Log::error('allByLoaction service error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    public function allByOutstand($request)
    {
        try {
            return $this->propertyRepository->allByOutstand($request);
        } catch (Exception $e) {
            Log::error('allByOutstand service error', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function create($request)
    {
        try {
            $property = $this->propertyRepository->create($request->all());
            return $property;
        } catch (Exception $e) {
            return null;
        }
    }

    public function update($id, Request $request)
    {
        try {
            $updated = $this->propertyRepository->update($id, $request->all());
            return $updated;
        } catch (Exception $e) {
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $status = $this->propertyRepository->delete($id);
            return $status;
        } catch (Exception $e) {
            return null;
        }
    }

    public function restore($id)
    {
        try {
            $restored = $this->propertyRepository->restore($id);
            return $restored;
        } catch (Exception $e) {
            return null;
        }
    }

    public function forceDelete($id)
    {
        try {
            $status = $this->propertyRepository->forceDelete($id);
            return $status;
        } catch (Exception $e) {
            return null;
        }
    }
    public function getPropertiesByUser($userId)
    {
        try {
            $properties = $this->propertyRepository->getPropertiesByUser($userId);
            return $properties;
        } catch (Exception $e) {
            return null;
        }
    }
}
