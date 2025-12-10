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
        return $this->handleServiceResponseWithEmptyCheck(
            $all,
            "Thành công",
            "Lỗi không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function SearchType(Request $request)
    {
        $vali = $this->propertyTypeValidation->validatePropertyType($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $data = $this->propertyTypeService->getByType($request);
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Lỗi không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function create(Request $request)
    {
        $vali = $this->propertyTypeValidation->validatePropertyTypeCreate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->propertyTypeService->create($request);
        return $this->handleServiceResponse(
            $status,
            "Tạo loại bất động sản thành công",
            "Lỗi khi tạo mới loại bất động sản",
            201,
            500
        );
    }

    public function update(Request $request, $id)
    {
        $vali = $this->propertyTypeValidation->validatePropertyTypeUpdate($request, $id);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $propertyType = $this->propertyTypeService->update($request, $id);
        return $this->handleServiceResponse(
            $propertyType,
            "Cập nhật loại bất động sản thành công",
            "Lỗi khi cập nhật loại bất động sản",
            200,
            500
        );
    }

    public function delete($id)
    {
        $vali = $this->propertyTypeValidation->checkIdValidation($id, 'property_types');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $propertyType = $this->propertyTypeService->delete($id);
        return $this->handleServiceResponse(
            $propertyType,
            "Xóa loại bất động sản thành công",
            "Lỗi khi xóa loại bất động sản",
            200,
            500
        );
    }
}
