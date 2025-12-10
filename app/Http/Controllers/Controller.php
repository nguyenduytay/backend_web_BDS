<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Xử lý response từ Service và trả về JSON response
     *
     * @param mixed $data Dữ liệu từ Service
     * @param string $successMessage Thông báo khi thành công
     * @param string $errorMessage Thông báo khi lỗi
     * @param int $successStatusCode HTTP status code khi thành công
     * @param int $errorStatusCode HTTP status code khi lỗi
     * @return JsonResponse
     */
    protected function handleServiceResponse(
        $data,
        string $successMessage = 'Thành công',
        string $errorMessage = 'Có lỗi xảy ra',
        int $successStatusCode = 200,
        int $errorStatusCode = 500
    ): JsonResponse {
        if ($data !== null) {
            return ApiResponse::success($data, $successMessage, $successStatusCode);
        }
        return ApiResponse::error($errorMessage, null, $errorStatusCode);
    }

    /**
     * Xử lý response từ Service với kiểm tra empty collection
     *
     * @param mixed $data Dữ liệu từ Service
     * @param string $successMessage Thông báo khi thành công
     * @param string $errorMessage Thông báo khi lỗi hoặc empty
     * @param int $successStatusCode HTTP status code khi thành công
     * @param int $errorStatusCode HTTP status code khi lỗi
     * @return JsonResponse
     */
    protected function handleServiceResponseWithEmptyCheck(
        $data,
        string $successMessage = 'Thành công',
        string $errorMessage = 'Không tìm thấy dữ liệu',
        int $successStatusCode = 200,
        int $errorStatusCode = 404
    ): JsonResponse {
        if ($data === null) {
            return ApiResponse::error($errorMessage, null, $errorStatusCode);
        }

        // Kiểm tra nếu là collection hoặc array rỗng
        if (is_countable($data) && count($data) === 0) {
            return ApiResponse::error($errorMessage, null, $errorStatusCode);
        }

        return ApiResponse::success($data, $successMessage, $successStatusCode);
    }


    /**
     * Xử lý validation errors
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return JsonResponse|null
     */
    protected function handleValidationErrors($validator): ?JsonResponse
    {
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ApiResponse::error(
                is_string($errors) ? $errors : $errors->first(),
                is_string($errors) ? null : $errors,
                422
            );
        }
        return null;
    }
}
