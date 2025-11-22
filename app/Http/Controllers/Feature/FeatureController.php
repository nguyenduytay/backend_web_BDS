<?php

namespace App\Http\Controllers\Feature;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\FeatureValidation;
use App\Services\FeatureService;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    protected $featureService;
    protected $featureValidation;

    public function __construct(FeatureService $featureService, FeatureValidation $featureValidation)
    {
        $this->featureService = $featureService;
        $this->featureValidation = $featureValidation;
    }

    public function all()
    {
        $all = $this->featureService->getAllFeatures();
        if ($all != null) {
            return ApiResponse::success($all);
        }
        return ApiResponse::error('Không tìm thấy dữ liệu', 404);
    }

    public function SearchId($id)
    {
        $validator = $this->featureValidation->checkIdValidate($id);
        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->first(), 422);
        }
        $data =  $this->featureService->SearchId($id);
        if ($data != null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error('Không tìm thấy dữ liệu', 404);
    }

    public function create(Request $request)
    {
        $vali = $this->featureValidation->createValidate($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $status = $this->featureService->create($request);
        if ($status != null) {
            return ApiResponse::success($status);
        }
        return ApiResponse::error('Lỗi khi tạo mới feature', 500);
    }

    public function update(Request $request, $id)
    {
        $vali = $this->featureValidation->updateValidate($request, $id);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors()->first(), 422);
        }
        $status = $this->featureService->update($request, $id);
        if ($status != null) {
            return ApiResponse::success($status);
        }
        return ApiResponse::error('Lỗi khi cập nhật feature', 500);
    }

    public function delete($id)
    {
        $validator = $this->featureValidation->checkIdValidation($id, 'features');
        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->first(), 422);
        }
        $status = $this->featureService->delete($id);
        if ($status != null) {
            return ApiResponse::success($status);
        }
        return ApiResponse::error('Lỗi khi xóa feature', 500);
    }
}
