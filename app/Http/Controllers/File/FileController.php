<?php

namespace App\Http\Controllers\File;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * ⚠️ LỖ HỔNG BẢO MẬT: Path Traversal / Directory Traversal
     * 
     * Endpoint này cho phép tải file nhưng không kiểm tra đúng cách
     * Kẻ tấn công có thể sử dụng ../ để truy cập file ngoài thư mục cho phép
     * 
     * Ví dụ payload:
     * - /api/file/download?file=../../../../etc/passwd
     * - /api/file/download?file=../../../.env
     * - /api/file/download?file=../../config/database.php
     */
    public function download(Request $request)
    {
        $filePath = $request->input('file');
        
        if (!$filePath) {
            return ApiResponse::error('Tham số file là bắt buộc', null, 400);
        }
        
        // ⚠️ LỖ HỔNG: Không validate và sanitize đường dẫn file
        // Cho phép path traversal với ../ hoặc ..\\
        
        // Cách không an toàn - trực tiếp sử dụng user input
        $fullPath = storage_path('app/public/' . $filePath);
        
        // Kiểm tra file có tồn tại không
        if (!file_exists($fullPath)) {
            return ApiResponse::error('File không tồn tại', null, 404);
        }
        
        // Trả về file - không kiểm tra xem file có nằm trong thư mục cho phép không
        return response()->download($fullPath);
    }
    
    /**
     * Endpoint khác để xem file (read file content)
     */
    public function view(Request $request)
    {
        $filePath = $request->input('file');
        
        if (!$filePath) {
            return ApiResponse::error('Tham số file là bắt buộc', null, 400);
        }
        
        // ⚠️ LỖ HỔNG: Tương tự như download
        $fullPath = storage_path('app/public/' . $filePath);
        
        if (!file_exists($fullPath)) {
            return ApiResponse::error('File không tồn tại', null, 404);
        }
        
        // Đọc và trả về nội dung file - nguy hiểm nếu là file nhạy cảm
        $content = file_get_contents($fullPath);
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}

