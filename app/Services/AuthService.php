<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Repositories\UsersRepository\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function register($request)
    {
        return  $this->usersRepository->create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'phone'    => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'avatar'   => null,
            'role'     => $request->input('role', 'user'),
        ]);
    }

    public function login($request)
    {
        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $token;
        }
        return null;
    }

    public function logout($accessToken)
    {
        $token = PersonalAccessToken::findToken($accessToken);
        if ($token) {
            $token->delete();
            return true;
        } else {
            return false;
        }
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }
        $token = $user->currentAccessToken();
        if (!$token || !$token instanceof PersonalAccessToken) {
            return null;
        }
        $token->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }
        return $user;
    }
    // Gửi email chứa link reset mật khẩu
    public function forgotPassword(Request $request)
    {
        $response = Password::broker()->sendResetLink($request->only('email'));
        return $response;
    }

    public function resetPassword(Request $request)
    {
        try {
            $response = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($response === Password::PASSWORD_RESET) {
                return ApiResponse::success(null, 'Mật khẩu của bạn đã được đặt lại thành công.');
            }
            return ApiResponse::error('Không thể đặt lại mật khẩu, token có thể không hợp lệ.', null, 400);
        } catch (\Exception $e) {
            return ApiResponse::error('Đã có lỗi xảy ra: ' . $e->getMessage(), null, 500);
        }
    }
}
