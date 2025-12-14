<?php

namespace App\Http\Controllers\Location;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\LocationValidation;
use App\Services\LocationService;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Spatie\FlareClient\Api;

class LocationController extends Controller
{
    protected $locationService;
    protected $locationValidation;

    public function __construct(LocationService $locationService, LocationValidation $locationValidation)
    {
        $this->locationService = $locationService;
        $this->locationValidation = $locationValidation;
    }

    public function all()
    {
            $all = $this->locationService->getAllLocations();
        return $this->handleServiceResponseWithEmptyCheck(
            $all,
            "Thành công",
            "Không có dữ liệu",
            200,
            404
        );
    }

    public function searchCity(Request $request)
    {
        $search = $this->locationService->search($request);
        return $this->handleServiceResponseWithEmptyCheck(
            $search,
            "Thành công",
            "Không tìm thấy kết quả nào.",
            200,
            404
        );
    }

    public function searchId($id)
    {
        $data = $this->locationService->show($id);
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu.",
            200,
            404
        );
    }

    public function create(Request $request)
    {
            $vali = $this->locationValidation->validateLocationCreate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
            $location = $this->locationService->create($request);
        return $this->handleServiceResponse(
            $location,
            "Tạo mới thành công",
            "Dữ liệu đã tồn tại.",
            201,
            400
        );
    }

    public function update(Request $request)
    {
            $vali = $this->locationValidation->validateLocationUpdate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
            $status = $this->locationService->update($request);
        return $this->handleServiceResponse(
            $status,
            "Cập nhật thành công",
            "Dữ liệu không hợp lệ.",
            200,
            400
        );
    }

    public function delete(Request $request)
    {
            $vali = $this->locationValidation->validateLocationDelete($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }
            $status = $this->locationService->delete($request);
        return $this->handleServiceResponse(
            $status,
            "Xóa thành công",
            "Không tìm thấy dữ liệu.",
            200,
            404
        );
    }

    public function cities(Request $request)
    {
        $cities = $this->locationService->getUniqueCities($request);
        return $this->handleServiceResponseWithEmptyCheck(
            $cities,
            "Thành công",
            "Không tìm thấy thành phố nào.",
            200,
            404
        );
    }

    public function districts($city)
    {
            $districts = $this->locationService->districts($city);
        return $this->handleServiceResponseWithEmptyCheck(
            $districts,
            "Thành công",
            "Không tìm thấy quận/huyện nào.",
            200,
            404
        );
    }
}
