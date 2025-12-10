<?php

namespace App\Http\Controllers\Property;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyValidation;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyService;
    protected $propertyValidation;

    public function __construct(PropertyService $propertyService, PropertyValidation $propertyValidation)
    {
        $this->propertyService = $propertyService;
        $this->propertyValidation = $propertyValidation;
    }

    public function all(Request $request)
    {
        $data = $this->propertyService->getAllProperties($request);
        return $this->handleServiceResponse(
            $data,
            'Lấy danh sách properties thành công',
            'Lỗi khi lấy danh sách properties',
            200,
            500
        );
    }

    public function searchId($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $data = $this->propertyService->show($id);
        return $this->handleServiceResponse(
            $data,
            'Lấy thông tin property thành công',
            'Lỗi khi lấy thông tin property',
            200,
            500
        );
    }

    public function allByPropertyType($propertyTypeId, Request $request)
    {
        $vali = $this->propertyValidation->checkIdValidation($propertyTypeId, 'property_types', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $data = $this->propertyService->allByPropertyType($propertyTypeId, $request);
        return $this->handleServiceResponse(
            $data,
            'Lấy danh sách properties thành công',
            'Lỗi khi lấy danh sách properties',
            200,
            500
        );
    }

    public function allByLoaction(Request $request)
    {
        $data = $this->propertyService->allByLoaction($request);
        return $this->handleServiceResponse(
            $data,
            'Lấy danh sách thành công',
            'Lỗi khi lấy danh sách properties theo location',
            200,
            500
        );
    }

    public function allByOutstand(Request $request)
    {
        $data = $this->propertyService->allByOutstand($request);
        if ($data != null && ($data->count() > 0 || (method_exists($data, 'total') && $data->total() > 0))) {
            return ApiResponse::success($data, 'Lấy danh sách thành công');
        }
        return ApiResponse::error('Lỗi khi lấy danh sách properties nổi bật', 500);
    }

    public function create(Request $request)
    {
        $vali = $this->propertyValidation->validateCreateAndUpdate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $status = $this->propertyService->create($request);
        return $this->handleServiceResponse(
            $status,
            'Tạo mới property thành công',
            'Lỗi khi tạo mới property',
            201,
            500
        );
    }

    public function update(Request $request, $id)
    {
        $valiId = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($valiId)) {
            return $valiError;
        }

        $valiUpdate = $this->propertyValidation->validateCreateAndUpdate($request);
        if ($valiError = $this->handleValidationErrors($valiUpdate)) {
            return $valiError;
        }

        $status = $this->propertyService->update($id, $request);
        return $this->handleServiceResponse(
            $status,
            'Cập nhật property thành công',
            'Lỗi khi cập nhật property',
            200,
            500
        );
    }

    public function delete($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $status = $this->propertyService->delete($id);
        return $this->handleServiceResponse(
            $status,
            'Xóa property thành công',
            'Lỗi khi xóa property',
            200,
            500
        );
    }

    public function restore($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $status = $this->propertyService->restore($id);
        return $this->handleServiceResponse(
            $status,
            'Khôi phục property thành công',
            'Lỗi khi khôi phục property',
            200,
            500
        );
    }

    public function forceDelete($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $property = $this->propertyService->forceDelete($id);
        return $this->handleServiceResponse(
            $property,
            'Xóa property thành công',
            'Lỗi khi xóa property',
            200,
            500
        );
    }

    public function propertiesByUser($userId)
    {
        $vali = $this->propertyValidation->checkIdValidation($userId, 'users', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $properties = $this->propertyService->getPropertiesByUser($userId);
        if ($properties != null && !$properties->isEmpty()) {
            return ApiResponse::success($properties, 'Lấy danh sách bất động sản của người dùng thành công');
        }
        return ApiResponse::error('Lỗi khi lấy danh sách properties của user', 500);
    }
}
