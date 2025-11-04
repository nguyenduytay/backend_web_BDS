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
        if (!$request->user()) {
            return ApiResponse::error('Unauthorized', 403);
        }

        // Lấy role của user
        $userRole = $request->user()->role;

        // Nếu user role không nằm trong danh sách cho phép
        if (!in_array($userRole, $roles)) {
            return ApiResponse::error('Unauthorized', 403);
        }

        return $next($request);
    }
}
