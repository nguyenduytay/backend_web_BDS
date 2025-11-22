<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Repositories\UsersRepository\UsersRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService extends BaseService
{
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function register($request)
    {
        return $this->execute(function () use ($request) {
            return $this->usersRepository->create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'phone'    => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'avatar'   => null,
                'role'     => $request->input('role', 'user'),
            ]);
        }, 'AuthService::register');
    }

    public function login($request)
    {
        return $this->execute(function () use ($request) {
            $credentials = [
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Kiểm tra và xóa token cũ nếu vượt quá giới hạn
                $maxTokens     = config('security.token.max_tokens_per_user', 5);
                $currentTokens = PersonalAccessToken::where('tokenable_id', $user->id)
                    ->where('tokenable_type', get_class($user))
                    ->count();

                if ($currentTokens >= $maxTokens) {
                    $oldestToken = PersonalAccessToken::where('tokenable_id', $user->id)
                        ->where('tokenable_type', get_class($user))
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($oldestToken) {
                        $oldestToken->delete();
                    }
                }

                return $user->createToken('auth_token')->plainTextToken;
            }
            return null;
        }, 'AuthService::login');
    }

    public function logout($accessToken)
    {
        return $this->execute(function () use ($accessToken) {
            $token = PersonalAccessToken::findToken($accessToken);
            if ($token) {
                $token->delete();
                return true;
            }
            return false;
        }, 'AuthService::logout');
    }

    public function refresh(Request $request)
    {
        return $this->execute(function () use ($request) {
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
        }, 'AuthService::refresh');
    }

    public function me(Request $request)
    {
        return $this->execute(function () use ($request) {
            $user = $request->user();
            if (!$user) {
                return null;
            }
            return $user;
        }, 'AuthService::me');
    }

    public function forgotPassword(Request $request)
    {
        return $this->execute(function () use ($request) {
            return Password::broker()->sendResetLink($request->only('email'));
        }, 'AuthService::forgotPassword');
    }

    public function resetPassword(Request $request)
    {
        return $this->execute(function () use ($request) {
            $response = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password'       => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($response === Password::PASSWORD_RESET) {
                return ApiResponse::success(null, 'Mật khẩu của bạn đã được đặt lại thành công.');
            }
            return ApiResponse::error('Không thể đặt lại mật khẩu, token có thể không hợp lệ.', null, 400);
        }, 'AuthService::resetPassword');
    }
}
