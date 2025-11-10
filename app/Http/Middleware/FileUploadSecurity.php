<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileUploadSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('file') || $request->hasFile('image')) {
            $file = $request->file('file') ?? $request->file('image');

                                                                   // Kiểm tra kích thước file
            $maxSize = config('filesystems.max_file_size', 10240); // 10MB default
            if ($file->getSize() > $maxSize * 1024) {
                return ApiResponse::error(
                    "File quá lớn. Kích thước tối đa cho phép: {$maxSize}KB",
                    null,
                    413
                );
            }

            // Kiểm tra loại file
            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'text/plain',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

            if (! in_array($file->getMimeType(), $allowedMimeTypes)) {
                return ApiResponse::error(
                    'Loại file không được hỗ trợ. Chỉ chấp nhận: JPG, PNG, GIF, WEBP, PDF, DOC, DOCX, TXT',
                    null,
                    415
                );
            }

            // Kiểm tra extension
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
            $extension         = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, $allowedExtensions)) {
                return ApiResponse::error(
                    'Phần mở rộng file không được hỗ trợ',
                    null,
                    415
                );
            }

            // Kiểm tra tên file
            $filename = $file->getClientOriginalName();
            if (strlen($filename) > 255) {
                return ApiResponse::error(
                    'Tên file quá dài. Tối đa 255 ký tự',
                    null,
                    400
                );
            }

            // Kiểm tra ký tự đặc biệt trong tên file
            if (preg_match('/[<>:"\/\\|?*]/', $filename)) {
                return ApiResponse::error(
                    'Tên file chứa ký tự không hợp lệ',
                    null,
                    400
                );
            }

            // Kiểm tra nội dung file (basic)
            $this->validateFileContent($file);
        }

        return $next($request);
    }

    /**
     * Validate file content for security
     */
    private function validateFileContent($file): void
    {
        $mimeType = $file->getMimeType();

        // Kiểm tra file ảnh
        if (str_starts_with($mimeType, 'image/')) {
            $this->validateImageFile($file);
        }

        // Kiểm tra file PDF
        if ($mimeType === 'application/pdf') {
            $this->validatePdfFile($file);
        }
    }

    /**
     * Validate image file
     */
    private function validateImageFile($file): void
    {
        try {
            $imageInfo = getimagesize($file->getPathname());

            if ($imageInfo === false) {
                throw new \Exception('File không phải là ảnh hợp lệ');
            }

            // Kiểm tra kích thước ảnh
            $maxWidth  = 4000;
            $maxHeight = 4000;

            if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                throw new \Exception("Kích thước ảnh quá lớn. Tối đa: {$maxWidth}x{$maxHeight}px");
            }
        } catch (\Exception $e) {
            throw new \Exception('File ảnh không hợp lệ: ' . $e->getMessage());
        }
    }

    /**
     * Validate PDF file
     */
    private function validatePdfFile($file): void
    {
        $content = file_get_contents($file->getPathname(), false, null, 0, 1024);

        // Kiểm tra PDF header
        if (! str_starts_with($content, '%PDF-')) {
            throw new \Exception('File không phải là PDF hợp lệ');
        }

        // Kiểm tra JavaScript trong PDF (potential security risk)
        if (stripos($content, '/JavaScript') !== false || stripos($content, '/JS') !== false) {
            throw new \Exception('PDF chứa JavaScript không được phép');
        }
    }
}
