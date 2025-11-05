<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kiểm tra đăng nhập
        if (!$request->user()) {
            return ApiResponse::error('Unauthorized', 403);
        }

        // Kiểm tra quyền truy cập
        $userRole = $request->user()->role;
        if (!in_array($userRole, $roles)) {
            return ApiResponse::error('Bạn không có quyền truy cập.', 403);
        }

        return $next($request);
    }
}
