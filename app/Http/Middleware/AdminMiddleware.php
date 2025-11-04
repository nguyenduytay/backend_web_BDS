<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user có login và role là admin
        if (!$request->user() || $request->user()->role !== 'admin') {
            return ApiResponse::error('Bạn không có quyền truy cập.', 403);
        }
        return $next($request);
    }
}
