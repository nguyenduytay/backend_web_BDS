<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestedUserId = (int) $request->id;
        $authenticatedUser = $request->user();
       dd($requestedUserId, $authenticatedUser);
        // Admin có quyền xem tất cả users
        if ($authenticatedUser->role === 'admin' || $authenticatedUser->id === $requestedUserId) {
            return $next($request);
        }

        // Nếu không có quyền
        return response()->json([
            'success' => false,
            'message' => 'Bạn không có quyền để dùng chức năng với User này'
        ], 403);
    }
}
