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
        $data = $this->contactService->getAllContacts();
        return $this->handleServiceResponseWithEmptyCheck(
            $data,
            "Thành công",
            "Không có dữ liệu",
            200,
            404
        );
    }

    public function search(Request $request)
    {
        $vali = $this->contactValidation->searchValidation($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $data = $this->contactService->search($request);
        return $this->handleServiceResponseWithEmptyCheck(
            $data,
            "Thành công",
            "Không có dữ liệu",
            200,
            404
        );
    }

    public function create(Request $request)
    {
        $vali = $this->contactValidation->createValidation($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->contactService->create($request);
        return $this->handleServiceResponse(
            $status,
            'Tạo liên hệ thành công',
            'Tạo liên hệ thất bại',
            201,
            500
        );
    }

    public function update(Request $request, $id)
    {
        $vali = $this->contactValidation->updateValidation($request, $id);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->contactService->update($request, $id);
        return $this->handleServiceResponse(
            $status,
            'Cập nhật liên hệ thành công',
            'Cập nhật liên hệ thất bại',
            200,
            500
        );
    }

    public function delete($id)
    {
        $vali = $this->contactValidation->checkIdValidation($id, 'contacts');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
        $status = $this->contactService->delete($id);
        return $this->handleServiceResponse(
            $status,
            'Xóa liên hệ thành công',
            'Xóa liên hệ thất bại',
            200,
            500
        );
    }

    /**
     * User: Liên hệ người bán - Lấy thông tin contact của property owner
     */
    public function contactSeller($propertyId)
    {
        $vali = $this->contactValidation->checkIdValidation($propertyId, 'properties', 'id');
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $contact = $this->contactService->getSellerContact($propertyId);
        return $this->handleServiceResponse(
            $contact,
            'Lấy thông tin liên hệ người bán thành công',
            'Không tìm thấy thông tin liên hệ',
            200,
            404
        );
    }
}
