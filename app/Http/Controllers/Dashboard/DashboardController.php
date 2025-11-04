<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\DashBoardValidation;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $dashboardValidation;

    public function __construct(DashboardService $dashboardService, DashBoardValidation $dashboardValidation)
    {
        $this->dashboardService = $dashboardService;
        $this->dashboardValidation = $dashboardValidation;
    }

    public function getStats()
    {
        $data = $this->dashboardService->getStats();
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error("Không tìm thấy dữ liệu");
    }

    public function getPropertyStats()
    {
        $data = $this->dashboardService->getPropertyStats();
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error("Không tìm thấy dữ liệu");
    }

    public function getUserStats()
    {
        $data = $this->dashboardService->getUserStats();
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error("Không tìm thấy dữ liệu");
    }

    public function getRecentProperties()
    {
        $data = $this->dashboardService->getRecentProperties();
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error("Không tìm thấy dữ liệu");
    }

    public function getRecentUsers()
    {
        $data = $this->dashboardService->getRecentUsers();
        if ($data !== null) {
            return ApiResponse::success($data);
        }
        return ApiResponse::error("Không tìm thấy dữ liệu");
    }
}
