<?php

namespace App\Http\Controllers\Property;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyValidation;
use App\Services\PropertyService;
use App\Models\Property;
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
        if ($data != null) {
            return ApiResponse::success($data, 'Lấy danh sách properties thành công');
        }
        return ApiResponse::error('Không tìm thấy properties');
    }

    public function searchId($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $data =  $this->propertyService->show($id);
        if ($data != null) {
            return ApiResponse::success($data, 'Lấy thông tin property thành công');
        }
        return ApiResponse::error('Không tìm thấy property', 500);
    }
    public function allByPropertyType($propertyTypeId, Request $request)
    {
        $vali = $this->propertyValidation->checkIdValidation($propertyTypeId, 'property_types', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $data = $this->propertyService->allByPropertyType($propertyTypeId, $request);
        if ($data != null) {
            return ApiResponse::success($data, 'Lấy danh sách properties thành công');
        }
        return ApiResponse::error('Không tìm thấy property');
    }
    public function allByLoaction(Request $request)
    {
        $data = $this->propertyService->allByLoaction($request);
        if ($data != null) {
            return ApiResponse::success($data, 'Lấy danh sách thành công');
        }
        return ApiResponse::error('Không tìm thấy dữ liệu');
    }
    public function allByOutstand(Request $request)
    {
         $data = $this->propertyService->allByOutstand($request);
        if ($data != null) {
            return ApiResponse::success($data, 'Lấy danh sách thành công');
        }
        return ApiResponse::error('Không tìm thấy dữ liệu');
    }

    public function create(Request $request)
    {
        $vali = $this->propertyValidation->validateCreateAndUpdate($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->propertyService->create($request);
        if ($status != null) {
            if ($status === false) {
                return ApiResponse::error('Tạo mới property thất bại', 422);
            } else {
                return ApiResponse::success($status, 'Tạo mới property thành công');
            }
        }
        return ApiResponse::error('Tạo mới property thất bại', 400);
    }

    public function update(Request $request, $id)
    {
        $valiId = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($valiId->fails()) {
            return ApiResponse::error($valiId->errors(), 422);
        }
        $valiUpdate = $this->propertyValidation->validateCreateAndUpdate($request);
        if ($valiUpdate->fails()) {
            return ApiResponse::error($valiUpdate->errors(), 422);
        }
        $status = $this->propertyService->update($id, $request);
        if ($status != null) {
            if ($status === false) {
                return ApiResponse::error('Cập nhật property thất bại', 422);
            } else {
                return ApiResponse::success($status, 'Cập nhật property thành công');
            }
        }
        return ApiResponse::error('Cập nhật property thất bại', 400);
    }

    public function delete($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->propertyService->delete($id);
        if ($status != null) {
            if ($status === false) {
                return ApiResponse::error('Xóa property thất bại', 422);
            } else {
                return ApiResponse::success($status, 'Xóa property thành công');
            }
        }
        return ApiResponse::error('Xóa property thất bại', 400);
    }

    public function restore($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->propertyService->restore($id);
        if ($status != null) {
            return ApiResponse::success($status, 'Khôi phục property thành công');
        }
        return ApiResponse::error('Khôi phục property thất bại', 400);
    }

    public function forceDelete($id)
    {
        $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $property = $this->propertyService->forceDelete($id);
        if ($property != null) {
            return ApiResponse::success($property, 'Xóa property thành công');
        }
        return ApiResponse::error('Xóa property thất bại', 400);
    }
    public function propertiesByUser($userId)
    {
        $vali = $this->propertyValidation->checkIdValidation($userId, 'users', 'id');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $properties = $this->propertyService->getPropertiesByUser($userId);
        if ($properties->isEmpty()) {
            return ApiResponse::error('Người dùng không có bất động sản nào', 404);
        }
        return ApiResponse::success($properties, 'Lấy danh sách bất động sản của người dùng thành công');
    }
}
