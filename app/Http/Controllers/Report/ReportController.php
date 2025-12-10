<?php

namespace App\Http\Controllers\Report;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // Báo cáo properties theo tháng
    public function getPropertiesMonthly()
    {
        $data = $this->reportService->getPropertiesMonthly();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Lỗi khi lấy dữ liệu",
            200,
            500
        );
    }

    // Báo cáo users theo tháng
    public function getUsersMonthly()
    {
        $data = $this->reportService->getUsersMonthly();
        return $this->handleServiceResponse(
            $data,
            "Thành công",
            "Lỗi khi lấy dữ liệu",
            200,
            500
        );
    }

    // Xuất CSV properties
    public function exportProperties(): StreamedResponse|JsonResponse|null
    {
        $response = $this->reportService->exportProperties();
        if ($response === null) {
            return ApiResponse::error('Lỗi khi xuất dữ liệu', null, 500);
        }
        return $response;
    }

    // Xuất CSV users
    public function exportUsers(): StreamedResponse|JsonResponse|null
    {
        $response = $this->reportService->exportUsers();
        if ($response === null) {
            return ApiResponse::error('Lỗi khi xuất dữ liệu', null, 500);
        }
        return $response;
    }
}
