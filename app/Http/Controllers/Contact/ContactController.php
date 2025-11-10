<?php

namespace App\Http\Controllers\Contact;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\ContactValidation;
use App\Services\ContactService;
use App\Models\Contact;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;

class ContactController extends Controller
{
    protected $contactService;
    protected $contactValidation;

    public function __construct(ContactService $contactService, ContactValidation $contactValidation)
    {
        $this->contactService = $contactService;
        $this->contactValidation = $contactValidation;
    }

    public function all()
    {
        $data =  $this->contactService->getAllContacts();
        if ($data != null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error('Không có dữ liệu', 404);
    }

    public function search(Request $request)
    {
        $vali = $this->contactValidation->searchValidation($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $data = $this->contactService->search($request);
        if ($data != null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error('Không có dữ liệu', 404);
    }

    public function create(Request $request)
    {
        $vali = $this->contactValidation->createValidation($request);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->contactService->create($request);
        if ($status) {
            return ApiResponse::success('Tạo liên hệ thành công', 201);
        }
        return ApiResponse::error('Tạo liên hệ thất bại', 500);
    }

    public function update(Request $request, $id)
    {
        $vali = $this->contactValidation->updateValidation($request, $id);
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->contactService->update($request, $id);
        if ($status) {
            return ApiResponse::success('Cập nhật liên hệ thành công', 200);
        }
        return ApiResponse::error('Cập nhật liên hệ thất bại', 500);
    }

    public function delete($id)
    {
        $vali = $this->contactValidation->checkIdValidation($id, 'contacts');
        if ($vali->fails()) {
            return ApiResponse::error($vali->errors(), 422);
        }
        $status = $this->contactService->delete($id);
        if ($status) {
            return ApiResponse::success('Xóa liên hệ thành công', 200);
        }
        return ApiResponse::error('Xóa liên hệ thất bại', 500);
    }
}
