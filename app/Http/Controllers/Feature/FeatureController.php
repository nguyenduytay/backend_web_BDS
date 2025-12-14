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
        return $this->handleServiceResponseWithEmptyCheck(
            $all,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function searchId($id)
    {
        $validator = $this->featureValidation->checkIdValidate($id);
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
        }
        $data = $this->featureService->SearchId($id);
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function create(Request $request)
    {
        $vali = $this->featureValidation->createValidate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->featureService->create($request);
        return $this->handleServiceResponse(
            $status,
            "Tạo mới feature thành công",
            "Lỗi khi tạo mới feature",
            201,
            500
        );
    }

    public function update(Request $request, $id)
    {
        $vali = $this->featureValidation->updateValidate($request, $id);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->featureService->update($request, $id);
        return $this->handleServiceResponse(
            $status,
            "Cập nhật feature thành công",
            "Lỗi khi cập nhật feature",
            200,
            500
        );
    }

    public function delete($id)
    {
        $validator = $this->featureValidation->checkIdValidation($id, 'features');
        if ($valiError = $this->handleValidationErrors($validator)) {
            return $valiError;
        }
        $status = $this->featureService->delete($id);
        return $this->handleServiceResponse(
            $status,
            "Xóa feature thành công",
            "Lỗi khi xóa feature",
            200,
            500
        );
    }
}
