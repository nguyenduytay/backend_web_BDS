<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Validations\UserValidation;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected $authService;
    protected $validation;

    public function __construct(AuthService $authService, UserValidation $validation)
    {
        $this->authService = $authService;
        $this->validation = $validation;
    }

    // Đăng ký (API)
    public function register(Request $request)
    {
        $vali = $this->validation->validateCreate($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $user = $this->authService->register($request);
        return $this->handleServiceResponse(
            $user,
            'Đăng ký thành công',
            'Lỗi khi đăng ký',
            201,
            500
        );
    }

    // Đăng nhập (API)
    public function login(Request $request)
    {
        $vali = $this->validation->validateAuthLoginRequest($request);
        if ($valiError = $this->handleValidationErrors($vali)) {
            return $valiError;
        }

        $token = $this->authService->login($request);
        return $this->handleServiceResponse(
            $token,
            'Đăng nhập thành công',
            'Đăng nhập thất bại',
            200,
            401
        );
    }

    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        if (!$accessToken) {
            return ApiResponse::error('Không tìm thấy token trong request.', null, 401);
        }
        $token = PersonalAccessToken::findToken($accessToken);
        if (!$token) {
            return ApiResponse::error('Token không hợp lệ hoặc đã hết hạn.', null, 401);
        }

        $check = $this->authService->logout($accessToken);
        return $this->handleServiceResponse(
            $check ? true : null,
            'Đăng xuất thành công.',
            'Lỗi khi đăng xuất.',
            200,
            500
        );
    }

    public function refresh(Request $request)
    {
        $newToken = $this->authService->refresh($request);
        if ($newToken) {
            return ApiResponse::success(
                ['token' => $newToken],
                'Làm mới token thành công',
                200
            );
        }
        return ApiResponse::error('Lỗi khi làm mới token', null, 500);
    }

    public function me(Request $request)
    {
        $user = $this->authService->me($request);
        return $this->handleServiceResponse(
            $user,
            'Lấy thông tin user thành công',
            'Không tìm thấy user.',
            200,
            401
        );
    }

    public function forgotPassword(Request $request)
    {
        // ⚠️ LỖ HỔNG BẢO MẬT: User Enumeration
        // Endpoint này tiết lộ thông tin về email có tồn tại hay không
        // Thông qua phản hồi khác nhau giữa email tồn tại và không tồn tại
        
        $email = $request->input('email');
        $user = \App\Models\User::where('email', $email)->first();
        
        if ($user) {
            // Email tồn tại - gửi reset link
            $response = $this->authService->forgotPassword($request);
            if ($response === Password::RESET_LINK_SENT) {
                return ApiResponse::success(null, 'Đường link đặt lại mật khẩu đã được gửi đến email của bạn.');
            }
            return ApiResponse::error('Lỗi khi gửi đường link đặt lại mật khẩu.', null, 500);
        } else {
            // Email không tồn tại - trả về lỗi khác
            return ApiResponse::error('Email không tồn tại trong hệ thống.', null, 404);
        }
    }

    public function resetPassword(Request $request)
    {
        return $this->authService->resetPassword($request);
    }
}
