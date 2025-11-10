<?php

namespace App\Http\Controllers\PropertyType;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyTypeValidation;
use App\Services\PropertyTypeService;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    protected $propertyTypeService;
    protected $propertyTypeValidation;

    public function __construct(PropertyTypeService $propertyTypeService, PropertyTypeValidation $propertyTypeValidation)
    {
        $this->propertyTypeService = $propertyTypeService;
        $this->propertyTypeValidation = $propertyTypeValidation;
    }

    public function all()
    {
        $all = $this->propertyTypeService->getAll();
        if ($all != null) {
            return ApiResponse::success($all);
        }

        return ApiResponse::error('Lỗi không tìm thấy dữ liệu', 404);
    }

    public function SearchType(Request $request)
    {
         $vali = $this->propertyTypeValidation->validatePropertyType($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $data = $this->propertyTypeService->getByType($request);
        if ($data != null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error('Lỗi không tìm thấy dữ liệu', 404);
    }

    public function create(Request $request)
    {
        $vali = $this->propertyTypeValidation->validatePropertyTypeCreate($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $status =  $this->propertyTypeService->create($request);
        if ($status != null) {
            if ($status === false) {
                return ApiResponse::error('Lỗi Không thể tạo mới dữ liệu, có thể do loại bất động sản đã tồn tại', 400);
            }
            return ApiResponse::success($status, "Tạo loại bất động sản thành công");
        }
        return ApiResponse::error('Lỗi Không thể tạo mới dữ liệu', 404);
    }

    public function update(Request $request, $id)
    {
        $vali = $this->propertyTypeValidation->validatePropertyTypeUpdate($request, $id);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $propertyType = $this->propertyTypeService->update($request, $id);
        if ($propertyType != null) {
            if ($propertyType === false) {
                return ApiResponse::error('Lỗi Không thể cập nhật dữ liệu, có thể do loại bất động sản đang được sử dụng', 400);
            }
            return ApiResponse::success($propertyType, "Cập nhật loại bất động sản thành công");
        }
        return ApiResponse::error('Lỗi Không thể cập nhật dữ liệu', 404);
    }

    public function delete($id)
    {
        $vali = $this->propertyTypeValidation->checkIdValidation($id, 'property_types');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $propertyType = $this->propertyTypeService->delete($id);
        if ($propertyType != null) {
            if ($propertyType === false) {
                return ApiResponse::error('Lỗi Không thể xóa dữ liệu, có thể do loại bất động sản đang được sử dụng', 400);
            }
            return ApiResponse::success($propertyType, "Xóa loại bất động sản thành công");
        }
        return ApiResponse::error('Lỗi Không thể xóa dữ liệu', 404);
    }
}
