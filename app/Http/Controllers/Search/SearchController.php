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
        return $this->handleServiceResponse(
            $data,
            "Lấy thông tin thành công",
            "Lấy thông tin thất bại",
            200,
            500
        );
    }

    public function filter(Request $request)
    {
        $data = $this->searchService->filter($request);
        return $this->handleServiceResponse(
            $data,
            "Lấy thông tin thành công",
            "Lấy thông tin thất bại",
            200,
            500
        );
    }

    public function autocomplete(Request $request)
    {
        $data = $this->searchService->autocomplete($request);
        return $this->handleServiceResponse(
            $data,
            "Lấy thông tin thành công",
            "Lấy thông tin thất bại",
            200,
            500
        );
    }

    public function nearby(Request $request)
    {
        $data = $this->searchService->nearby($request);
        return $this->handleServiceResponse(
            $data,
            "Lấy thông tin thành công",
            "Lấy thông tin thất bại",
            200,
            500
        );
    }
}
