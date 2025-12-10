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
use Throwable;

class AuthService extends BaseService
{
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function register($request)
    {
        try {
            return $this->usersRepository->create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'phone'    => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'avatar'   => null,
                'role'     => $request->input('role', 'user'),
            ]);
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::register');
            return null;
        }
    }

    public function login($request)
    {
        try {
            $credentials = [
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
            ];

            if (Auth::attempt($credentials)) {
                /** @var \App\Models\User $user */
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
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::login');
            return null;
        }
    }

    public function logout($accessToken)
    {
        try {
            $token = PersonalAccessToken::findToken($accessToken);
            if ($token) {
                $token->delete();
                return true;
            }
            return false;
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::logout');
            return null;
        }
    }

    public function refresh(Request $request)
    {
        try {
            /** @var \App\Models\User|null $user */
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
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::refresh');
            return null;
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return null;
            }
            return $user;
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::me');
            return null;
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            return Password::broker()->sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::forgotPassword');
            return null;
        }
    }

    public function resetPassword(Request $request)
    {
        try {
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
        } catch (Throwable $e) {
            $this->handleException($e, 'AuthService::resetPassword');
            return null;
        }
    }
}
