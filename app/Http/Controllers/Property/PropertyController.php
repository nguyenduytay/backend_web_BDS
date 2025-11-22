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
        Log::info('PropertyController::allByPropertyType START', [
            'property_type_id' => $propertyTypeId,
            'request_params' => $request->all()
        ]);

        try {
            Log::info('PropertyController::allByPropertyType: Validating', [
                'property_type_id' => $propertyTypeId
            ]);

            $vali = $this->propertyValidation->checkIdValidation($propertyTypeId, 'property_types', 'id');
            if ($vali->fails()) {
                Log::warning('PropertyController::allByPropertyType: Validation failed', [
                    'property_type_id' => $propertyTypeId,
                    'errors' => $vali->errors()->toArray()
                ]);
                return ApiResponse::error($vali->errors(), 422);
            }

            Log::info('PropertyController::allByPropertyType: Calling service', [
                'property_type_id' => $propertyTypeId
            ]);

            $data = $this->propertyService->allByPropertyType($propertyTypeId, $request);

            Log::info('PropertyController::allByPropertyType: Service returned', [
                'property_type_id' => $propertyTypeId,
                'data_is_null' => $data === null,
                'data_type' => gettype($data)
            ]);

            if ($data != null) {
                Log::info('PropertyController::allByPropertyType SUCCESS', [
                    'property_type_id' => $propertyTypeId
                ]);
                return ApiResponse::success($data, 'Lấy danh sách properties thành công');
            }

            Log::warning('PropertyController::allByPropertyType: No data found', [
                'property_type_id' => $propertyTypeId
            ]);
            return ApiResponse::error('Không tìm thấy property', 404);
        } catch (\Exception $e) {
            Log::error('PropertyController::allByPropertyType ERROR', [
                'property_type_id' => $propertyTypeId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
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
            Log::error('allByLoaction controller error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties theo location', 500);
        }
    }
    public function allByOutstand(Request $request)
    {
        Log::info('PropertyController::allByOutstand START', [
            'request_params' => $request->all()
        ]);

        try {
            Log::info('PropertyController::allByOutstand: Calling service');

            $data = $this->propertyService->allByOutstand($request);

            Log::info('PropertyController::allByOutstand: Service returned', [
                'data_is_null' => $data === null,
                'data_type' => gettype($data),
                'has_count' => method_exists($data, 'count'),
                'has_total' => method_exists($data, 'total')
            ]);

            if ($data != null) {
                $count = method_exists($data, 'count') ? $data->count() : 0;
                $total = method_exists($data, 'total') ? $data->total() : 0;

                Log::info('PropertyController::allByOutstand: Data details', [
                    'count' => $count,
                    'total' => $total
                ]);

                if ($count > 0 || $total > 0) {
                    Log::info('PropertyController::allByOutstand SUCCESS');
                    return ApiResponse::success($data, 'Lấy danh sách thành công');
                }
            }

            Log::warning('PropertyController::allByOutstand: No data found');
            return ApiResponse::error('Không tìm thấy dữ liệu', 404);
        } catch (\Exception $e) {
            Log::error('PropertyController::allByOutstand ERROR', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponse::error('Lỗi khi lấy danh sách properties nổi bật', 500);
        }
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
