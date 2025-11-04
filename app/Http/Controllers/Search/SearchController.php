<?php

namespace App\Http\Controllers\Search;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        $data = $this->searchService->search($request);
        if ($data !== null) {
            return ApiResponse::success($data, "Lấy thông tin thành công");
        }
        return ApiResponse::error("Lấy thông tin thất bại");
    }

    public function filter(Request $request)
    {
        $data = $this->searchService->filter($request);
        if ($data !== null) {
            return ApiResponse::success($data, "Lấy thông tin thành công");
        }
        return ApiResponse::error("Lấy thông tin thất bại");
    }


    public function autocomplete(Request $request)
    {
        $data = $this->searchService->autocomplete($request);
        if ($data !== null) {
            return ApiResponse::success($data, "Lấy thông tin thành công");
        }
        return ApiResponse::error("Lấy thông tin thất bại");
    }

    public function nearby(Request $request)
    {
        $data = $this->searchService->nearby($request);
        if ($data !== null) {
            return ApiResponse::success($data, "Lấy thông tin thành công");
        }
        return ApiResponse::error("Lấy thông tin thất bại");
    }
}
