<?php

namespace App\Http\Controllers\PropertyFeature;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyFeatureValidation;
use App\Models\PropertyFeature;
use Illuminate\Http\Request;
use App\Services\PropertyFeatureService;

class PropertyFeatureController extends Controller
{
    protected $propertyFeatureService;
    protected $propertyFeatureValidation;

    public function __construct(PropertyFeatureService $propertyFeatureService, PropertyFeatureValidation $propertyFeatureValidation)
    {
        $this->propertyFeatureService = $propertyFeatureService;
        $this->propertyFeatureValidation = $propertyFeatureValidation;
    }

    public function all($propertyId)
    {
        $valiPropertyId = $this->propertyFeatureValidation->checkIdValidation($propertyId, 'properties', 'id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error(
                $valiPropertyId->errors()->first()
            );
        }
        $data = $this->propertyFeatureService->getFeaturesByProperty($propertyId);
        if ($data != null) {
            return ApiResponse::success(
                $data
            );
        }
        return ApiResponse::error(
            'Không tìm thấy dữ liệu'
        );
    }

    public function create(Request $request, $propertyId)
    {
        $valiPropertyId = $this->propertyFeatureValidation->checkIdValidation($propertyId, 'properties', 'id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error(
                $valiPropertyId->errors()->first()
            );
        }
        $valiFeatureId = $this->propertyFeatureValidation->checkIdValidation($request->feature_id, 'features', 'id');
        if ($valiFeatureId->fails()) {
            return ApiResponse::error(
                $valiFeatureId->errors()->first()
            );
        }
        $data = $this->propertyFeatureService->addFeatureToProperty($propertyId, $request->all());
        if ($data != null) {
            if ($data === false) {
                return ApiResponse::error(
                    'Thêm thất bại',
                );
            }
            return ApiResponse::success(
                $data
            );
        }
        return ApiResponse::error(
            'Thêm thất bại'
        );
    }

    public function sync(Request $request, $propertyId)
    {
        $valiPropertyId = $this->propertyFeatureValidation->checkIdValidation($propertyId, 'properties', 'id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error(
                $valiPropertyId->errors()->first()
            );
        }
        $data = $this->propertyFeatureService->syncFeaturesToProperty($propertyId, $request);
        if ($data != null) {
            if ($data === false) {
                return ApiResponse::error(
                    'Xóa thất bại',
                );
            }
            return ApiResponse::success(
                $data
            );
        }
        return ApiResponse::error(
            'Xóa thất bại'
        );
    }

    public function delete($propertyId, $featureId)
    {
        $valiPropertyId = $this->propertyFeatureValidation->checkIdValidation(
            $propertyId,
            'properties',
            'id'
        );
        if ($valiPropertyId->fails()) {
            return ApiResponse::error(
                $valiPropertyId->errors()->first()
            );
        }
        $valiFeatureId = $this->propertyFeatureValidation->checkIdValidation(
            $featureId,
            'features',
            'id'
        );
        if ($valiFeatureId->fails()) {
            return ApiResponse::error(
                $valiFeatureId->errors()->first()
            );
        }

        $status = $this->propertyFeatureService->removeFeatureFromProperty($propertyId, $featureId);
        if ($status !== null) {
            if ($status === false) {
                return ApiResponse::error('Xóa thất bại');
            }
            return ApiResponse::success($status, 'Xóa thành công');
        }
        return ApiResponse::error('Xóa thất bại');
    }
}
