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
        // ⚠️ LỖ HỔNG BẢO MẬT: Reflected XSS
        // Tham số 'q' được phản chiếu trực tiếp vào response mà không được escape
        // Cho phép kẻ tấn công chèn mã JavaScript độc hại
        
        $keyword = $request->input('q', '');
        
        // Lỗ hổng: Không escape output, trả về keyword trực tiếp trong response
        $data = $this->searchService->search($request);
        
        // ⚠️ LỖ HỔNG: Thêm keyword không được escape vào response data
        // Frontend có thể render trực tiếp dẫn đến XSS
        if ($data && method_exists($data, 'getCollection')) {
            $data->getCollection()->transform(function ($item) use ($keyword) {
                $item->search_keyword = $keyword; // ⚠️ Không escape - dễ bị XSS
                return $item;
            });
        }
        
        // Thêm keyword vào response message để demo XSS
        $message = "Kết quả tìm kiếm cho: " . $keyword; // ⚠️ Không escape
        
        return $this->handleServiceResponse(
            $data,
            $message, // ⚠️ Keyword không được escape - dễ bị XSS
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
