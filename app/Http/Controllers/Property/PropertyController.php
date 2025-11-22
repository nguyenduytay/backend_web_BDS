<?php

namespace App\Http\Controllers\Property;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyValidation;
use App\Services\PropertyService;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $data = $this->propertyService->getAllProperties($request);
            if ($data != null) {
                return ApiResponse::success($data, 'Lấy danh sách properties thành công');
            }
            return ApiResponse::error('Không tìm thấy properties', 404);
        } catch (\Exception $e) {
            Log::error('all properties controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties', 500);
        }
    }

    public function searchId($id)
    {
        try {
            $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $data = $this->propertyService->show($id);
            if ($data != null) {
                return ApiResponse::success($data, 'Lấy thông tin property thành công');
            }
            return ApiResponse::error('Không tìm thấy property', 404);
        } catch (\Exception $e) {
            Log::error('searchId controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy thông tin property', 500);
        }
    }
    public function allByPropertyType($propertyTypeId, Request $request)
    {
        try {
            $vali = $this->propertyValidation->checkIdValidation($propertyTypeId, 'property_types', 'id');
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }

            $data = $this->propertyService->allByPropertyType($propertyTypeId, $request);
            if ($data != null) {
                return ApiResponse::success($data, 'Lấy danh sách properties thành công');
            }
            return ApiResponse::error('Không tìm thấy property', 404);
        } catch (\Exception $e) {
            Log::error('allByPropertyType controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties', 500);
        }
    }
    public function allByLoaction(Request $request)
    {
        try {
            $data = $this->propertyService->allByLoaction($request);
            if ($data != null) {
                return ApiResponse::success($data, 'Lấy danh sách thành công');
            }
            return ApiResponse::error('Không tìm thấy dữ liệu', 404);
        } catch (\Exception $e) {
            Log::error('allByLoaction controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties theo location', 500);
        }
    }
    public function allByOutstand(Request $request)
    {
        try {
            $data = $this->propertyService->allByOutstand($request);
            if ($data != null && ($data->count() > 0 || (method_exists($data, 'total') && $data->total() > 0))) {
                return ApiResponse::success($data, 'Lấy danh sách thành công');
            }
            return ApiResponse::error('Không tìm thấy dữ liệu', 404);
        } catch (\Exception $e) {
            Log::error('allByOutstand controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties nổi bật', 500);
        }
    }

    public function create(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('create property controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi tạo mới property', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('update property controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi cập nhật property', 500);
        }
    }

    public function delete($id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('delete property controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi xóa property', 500);
        }
    }

    public function restore($id)
    {
        try {
            $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $status = $this->propertyService->restore($id);
            if ($status != null) {
                return ApiResponse::success($status, 'Khôi phục property thành công');
            }
            return ApiResponse::error('Khôi phục property thất bại', 400);
        } catch (\Exception $e) {
            Log::error('restore property controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi khôi phục property', 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            $vali = $this->propertyValidation->checkIdValidation($id, 'properties', 'id');
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $property = $this->propertyService->forceDelete($id);
            if ($property != null) {
                return ApiResponse::success($property, 'Xóa property thành công');
            }
            return ApiResponse::error('Xóa property thất bại', 400);
        } catch (\Exception $e) {
            Log::error('forceDelete property controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi xóa property', 500);
        }
    }

    public function propertiesByUser($userId)
    {
        try {
            $vali = $this->propertyValidation->checkIdValidation($userId, 'users', 'id');
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $properties = $this->propertyService->getPropertiesByUser($userId);
            if ($properties->isEmpty()) {
                return ApiResponse::error('Người dùng không có bất động sản nào', 404);
            }
            return ApiResponse::success($properties, 'Lấy danh sách bất động sản của người dùng thành công');
        } catch (\Exception $e) {
            Log::error('propertiesByUser controller error', [
                'error' => $e->getMessage()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties của user', 500);
        }
    }
}
