<?php

namespace App\Http\Controllers\PropertyImage;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyImageValidation;
use App\Services\PropertyImageService;
use Illuminate\Http\Request;

class PropertyImageController extends Controller
{
    protected $propertyImageService;
    protected $propertyImageValidation;

    public function __construct(PropertyImageService $propertyImageService, PropertyImageValidation $propertyImageValidation)
    {
        $this->propertyImageService = $propertyImageService;
        $this->propertyImageValidation = $propertyImageValidation;
    }

    public function all($propertyId)
    {
        $vali = $this->propertyImageValidation->checkIdValidation($propertyId, 'property_images', 'property_id');
        if ($vali->fails()) {
            return ApiResponse::error($vali, 400);
        }
        $data = $this->propertyImageService->getAllImages($propertyId);

        if ($data && $data->count() > 0) {
            return ApiResponse::success($data, "Lấy danh sách ảnh thành công");
        }

        return ApiResponse::error("Không tìm thấy ảnh", 404);
    }

    public function create(Request $request, $propertyId)
    {
        $vali = $this->propertyImageValidation->createValidation($request, $propertyId);
        if ($vali->fails()) {
            return ApiResponse::error($vali, 400);
        }
        $status = $this->propertyImageService->create($request, $propertyId);
        if ($status != null) {
            return ApiResponse::success($status, "Tạo ảnh thành công");
        }
        return ApiResponse::error("Lỗi khi tạo ảnh", 500);
    }

    public function show($propertyId, $imageId)
    {
        $valiPropertyId = $this->propertyImageValidation->checkIdValidation($propertyId, 'property_images', 'property_id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($valiPropertyId, 400);
        }
        $valiImageId = $this->propertyImageValidation->checkIdValidation($imageId, 'property_images', 'id');
        if ($valiImageId->fails()) {
            return ApiResponse::error($valiImageId, 400);
        }
        $data =  $this->propertyImageService->show($propertyId, $imageId);
        if ($data != null) {
            return ApiResponse::success($data, "Lấy ảnh thành công");
        }
        return ApiResponse::error("Không tìm thấy ảnh", 404);
    }

    public function update(Request $request, $propertyId, $imageId)
    {

        $valiPropertyId = $this->propertyImageValidation->checkIdValidation($propertyId, 'property_images', 'property_id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($valiPropertyId->errors()->first(), 400);
        }
        $valiImageId = $this->propertyImageValidation->checkIdValidation($imageId, 'property_images', 'id');
        if ($valiImageId->fails()) {
            return ApiResponse::error($valiImageId->errors()->first(), 400);
        }
        $valiRequest = $this->propertyImageValidation->updateValidation($request, $propertyId, $imageId);
        if ($valiRequest->fails()) {
            return ApiResponse::error($valiRequest->errors()->first(), 400);
        }
        $status = $this->propertyImageService->update($request, $propertyId, $imageId);
        if ($status != null) {
            return ApiResponse::success($status, "Cập nhật ảnh thành công");
        }
        return ApiResponse::error("Cập nhật ảnh thất bại", 400);
    }

    public function delete($propertyId, $imageId)
    {
        $valiPropertyId = $this->propertyImageValidation->checkIdValidation($propertyId, 'property_images', 'property_id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($valiPropertyId->errors()->first(), 400);
        }
        $valiImageId = $this->propertyImageValidation->checkIdValidation($imageId, 'property_images', 'id');
        if ($valiImageId->fails()) {
            return ApiResponse::error($valiImageId->errors()->first(), 400);
        }
        $status = $this->propertyImageService->delete($propertyId, $imageId);
        if ($status != null) {
            return ApiResponse::success($status, "Xóa ảnh thành công");
        }
        return ApiResponse::error("Lỗi khi xóa ảnh", 500);
    }

    public function deleteMultiple(Request $request, $propertyId)
    {
        $valiPropertyId = $this->propertyImageValidation->checkIdValidation($propertyId, 'property_images', 'property_id');
        if ($valiPropertyId->fails()) {
            return ApiResponse::error($valiPropertyId->errors()->first(), 400);
        }
        $status = $this->propertyImageService->deleteMultiple($request, $propertyId);
        if ($status != null) {
            return ApiResponse::success($status, "Xóa ảnh thành công");
        }
        return ApiResponse::error("Xóa ảnh thất bại", 404);
    }
    public function homeAvatars()
    {
        $data = $this->propertyImageService->getAllHomeAvatars();
        if ($data && $data->count() > 0) {
            return ApiResponse::success($data, "Lấy danh sách ảnh đại diện thành công");
        }
        return ApiResponse::error("Lấy danh sách ảnh đại diện thất bại", 404);
    }
}
