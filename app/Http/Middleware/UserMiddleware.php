<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user có login và role là admin
        if (!$request->user() || $request->user()->role !== 'user') {
            return ApiResponse::error('Bạn chưa có tài khoản.', 403);
        }
        return $next($request);
    }
}
