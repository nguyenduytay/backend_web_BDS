<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\PropertyRepository\PropertyRepositoryInterface;
use Throwable;

class PropertyService extends BaseService
{
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties($request)
    {
        try {
            return $this->propertyRepository->allProperty($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::getAllProperties');
            return null;
        }
    }

    public function show($id)
    {
        try {
            return $this->propertyRepository->find($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::show');
            return null;
        }
    }

    public function allByPropertyType($propertyTypeId, $request)
    {
        try {
            return $this->propertyRepository->allByPropertyType($propertyTypeId, $request);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::allByPropertyType');
            return null;
        }
    }

    public function allByLoaction($request)
    {
        try {
            return $this->propertyRepository->allByLoaction($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::allByLoaction');
            return null;
        }
    }

    public function allByOutstand($request)
    {
        try {
            return $this->propertyRepository->allByOutstand($request);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::allByOutstand');
            return null;
        }
    }

    public function create($request)
    {
        try {
            return $this->propertyRepository->create($request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::create');
            return null;
        }
    }

    public function update($id, Request $request)
    {
        try {
            return $this->propertyRepository->update($id, $request->all());
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::update');
            return null;
        }
    }

    public function delete($id)
    {
        try {
            return $this->propertyRepository->delete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::delete');
            return null;
        }
    }

    public function restore($id)
    {
        try {
            return $this->propertyRepository->restore($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::restore');
            return null;
        }
    }

    public function forceDelete($id)
    {
        try {
            return $this->propertyRepository->forceDelete($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::forceDelete');
            return null;
        }
    }

    public function getPropertiesByUser($userId)
    {
        try {
            return $this->propertyRepository->getPropertiesByUser($userId);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::getPropertiesByUser');
            return null;
        }
    }

    /**
     * Admin: Duyệt tin đăng (approve property)
     */
    public function approve($id)
    {
        try {
            $property = $this->propertyRepository->find($id);
            $property->status = 'available'; // Đặt status thành available khi duyệt
            $property->save();
            return $property;
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::approve');
            return null;
        }
    }

    /**
     * Admin: Ẩn tin đăng (hide property)
     */
    public function hide($id)
    {
        try {
            $property = $this->propertyRepository->find($id);
            $property->status = 'pending'; // Đặt status thành pending để ẩn
            $property->save();
            return $property;
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::hide');
            return null;
        }
    }

    /**
     * ⚠️ LỖ HỔNG BẢO MẬT: SQL Injection
     * Method này sử dụng repository có lỗ hổng SQL Injection để demo
     */
    public function getPropertyByIdVulnerable($id)
    {
        try {
            return $this->propertyRepository->findVulnerable($id);
        } catch (Throwable $e) {
            $this->handleException($e, 'PropertyService::getPropertyByIdVulnerable');
            throw $e; // Re-throw để controller có thể bắt và hiển thị lỗi SQL
        }
    }
}
