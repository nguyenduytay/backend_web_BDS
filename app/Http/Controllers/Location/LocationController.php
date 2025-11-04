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
            if ($all->isEmpty()) {
                return ApiResponse::error("Không có dữ liệu ", 404);
            }
            return ApiResponse::success($all, "Thành công", 200);
    }

    public function SearchCity(Request $request)
    {
            $search =  $this->locationService->search($request);
            if ($search->isEmpty()) {
                return ApiResponse::error("Không tìm thấy kết quả nào.", 404);
            }
            return ApiResponse::success($search, "Thành công", 200);
    }

    public function SeacrhId($id)
    {

            $data =  $this->locationService->show($id);
            if ($data) {
                return ApiResponse::success($data, "Thành công", 200);
            }
            return ApiResponse::error("Không tìm thấy dữ liệu.", 404);
    }

    public function create(Request $request)
    {

            $vali = $this->locationValidation->validateLocationCreate($request);
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $location = $this->locationService->create($request);
            if ($location) {
                return ApiResponse::success($location, "Tạo mới thành công", 201);
            }
            return ApiResponse::error("Dữ liệu đã tồn tại.", 400);

    }

    public function update(Request $request)
    {
            $vali = $this->locationValidation->validateLocationUpdate($request);
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $status = $this->locationService->update($request);
            if ($status) {
                return ApiResponse::success($status, "Cập nhật thành công", 200);
            }
            return ApiResponse::error("Dữ liệu không hợp lệ.", 400);
    }

    public function delete(Request $request)
    {
            $vali = $this->locationValidation->validateLocationDelete($request);
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $status = $this->locationService->delete($request);
            if ($status) {
                return ApiResponse::success($status, "Xóa thành công", 200);
            }
            return ApiResponse::error("Không tìm thấy dữ liệu.", 404);
    }

    public function cities(Request $request)
    {
            $cities =  $this->locationService->getUniqueCities($request);
            if ($cities->isEmpty())
                return ApiResponse::error("Không tìm thấy thành phố nào.", 404);
            return ApiResponse::success($cities, 200);
    }

    public function districts($city)
    {
            $districts = $this->locationService->districts($city);
            if ($districts->isEmpty())
                return ApiResponse::error("Không tìm thấy quận/huyện nào.", 404);
            return ApiResponse::success($districts, 200);
    }
}
