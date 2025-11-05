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
        try {
            $vali = $this->validation->validateCreate($request);
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $user = $this->authService->register($request);
            if ($user != null)
                return ApiResponse::success(null, 'Đăng ký thành công', 201);
            else
                return ApiResponse::error('Đăng ký thất bại', null, 400);
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return ApiResponse::error('Đăng ký thất bại. Vui lòng thử lại sau.', null, 400);
        }
    }
    // Đăng nhập (API)
    public function login(Request $request)
    {
        try {
            $vali = $this->validation->validateAuthLoginRequest($request);
            if ($vali->fails()) {
                return ApiResponse::error($vali->errors(), 422);
            }
            $token = $this->authService->login($request);
            if ($token != null) {
                return ApiResponse::success($token, 'Đăng nhập thành công');
            } else {
                return ApiResponse::error('Đăng nhập thất bại', null, 401);
            }
        } catch (\Exception $e) {
            \Log::error('Login failed', [
                'email' => $request->input('email'),
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return ApiResponse::error('Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin.', null, 400);
        }
    }
    public function logout(Request $request)
    {
        try {
            $accessToken = $request->bearerToken();
            if (!$accessToken) {
                return ApiResponse::error('Không tìm thấy token trong request.', null, 401);
            }
            $token = PersonalAccessToken::findToken($accessToken);
            if (!$token) {
                return ApiResponse::error('Token không hợp lệ hoặc đã hết hạn.', null, 401);
            }
            $check = $this->authService->logout($accessToken);
            if ($check)
                return ApiResponse::success(null, 'Đăng xuất thành công.', 200);
            else
                return ApiResponse::error('Đăng xuất thất bại.', null, 500);
        } catch (\Exception $e) {
            return ApiResponse::error('Đăng xuất thất bại: ' . $e->getMessage(), null, 500);
        }
    }
    public function refresh(Request $request)
    {
        try {
            $newToken = $this->authService->refresh($request);
            if ($newToken) {
                return ApiResponse::success(
                    ['token' => $newToken],
                    'Làm mới token thành công',
                    200
                );
            }

            return ApiResponse::error('Làm mới token thất bại', null, 401);
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Làm mới token thất bại: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    public function me(Request $request)
    {

        try {
            $user = $this->authService->me($request);
            if (!$user) {
                return ApiResponse::error('Không tìm thấy user.', null, 401);
            }

            return ApiResponse::success($user, 'Lấy thông tin user thành công', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Không thể lấy thông tin user: ' . $e->getMessage(), null, 500);
        }
    }
    public function forgotPassword(Request $request)
    {
        try {
            $response = $this->authService->forgotPassword($request);

            if ($response === Password::RESET_LINK_SENT) {
                return ApiResponse::success(null, 'Đường link đặt lại mật khẩu đã được gửi đến email của bạn.');
            }
            return ApiResponse::error('Không thể gửi đường link đặt lại mật khẩu.', null, 500);
        } catch (\Exception $e) {
            return ApiResponse::error('Đã có lỗi xảy ra: ' . $e->getMessage(), null, 500);
        }
    }
    public function resetPassword(Request $request)
    {
        return $this->authService->resetPassword($request);
    }
}
