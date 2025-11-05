<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\TransientToken;

class CheckTokenExpiry
{
    public function handle($request, Closure $next)
    {
        $accessToken = $request->user()->currentAccessToken();
        if ($accessToken instanceof PersonalAccessToken) {
            // Kiểm tra token đã quá 120 phút (2 giờ)
            if ($accessToken->created_at && $accessToken->created_at->diffInMinutes(now()) > 120) {
                $accessToken->delete(); // Xóa token
                return ApiResponse::error('Token đã hết hạn (hơn 2 giờ), vui lòng đăng nhập lại', 401);
            }
        }
        // Nếu là TransientToken (SPA) thì bỏ qua
        return $next($request);
    }
}
