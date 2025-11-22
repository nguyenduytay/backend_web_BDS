<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Trả về JSON response chung
     *
     * @param string|bool $status
     * @param string $message
     * @param mixed  $data
     * @param int    $statusCode
     * @return JsonResponse
     */
    public static function send($status, $message, $data = null, $statusCode = 200): JsonResponse
    {
        $response = [
            'status'  => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    // Shortcut thành công
    public static function success($data = null, $message = 'Thành công', $statusCode = 200): JsonResponse
    {
        return self::send('success', $message, $data, $statusCode);
    }

    // Shortcut thất bại
    public static function error($message = 'Có lỗi xảy ra', $errors = null, $statusCode = 400): JsonResponse
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
