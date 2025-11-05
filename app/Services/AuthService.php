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

class AuthService
{
    protected $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function register($request)
    {
        return $this->usersRepository->create([
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
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra và xóa token cũ nếu vượt quá giới hạn
            $maxTokens     = config('security.token.max_tokens_per_user', 5);
            $currentTokens = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)
                ->where('tokenable_type', get_class($user))
                ->count();

            if ($currentTokens >= $maxTokens) {
                $oldestToken = \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)
                    ->where('tokenable_type', get_class($user))
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($oldestToken) {
                    $oldestToken->delete();
                }
            }

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
        if (! $user) {
            return null;
        }
        $token = $user->currentAccessToken();
        if (! $token || ! $token instanceof PersonalAccessToken) {
            return null;
        }
        $token->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }
    public function me(Request $request)
    {
        $user = $request->user();
        if (! $user) {
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
                        'password'       => Hash::make($password),
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
