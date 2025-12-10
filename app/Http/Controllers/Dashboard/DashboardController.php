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
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function getPropertyStats()
    {
        $data = $this->dashboardService->getPropertyStats();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function getUserStats()
    {
        $data = $this->dashboardService->getUserStats();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function getRecentProperties()
    {
        $data = $this->dashboardService->getRecentProperties();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }

    public function getRecentUsers()
    {
        $data = $this->dashboardService->getRecentUsers();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Không tìm thấy dữ liệu",
            200,
            404
        );
    }
}
