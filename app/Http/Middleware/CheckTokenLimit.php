<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $maxTokens     = config('security.token.max_tokens_per_user', 5);
        $currentTokens = PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', get_class($user))
            ->count();

        if ($currentTokens >= $maxTokens) {
            // Xóa token cũ nhất
            $oldestToken = PersonalAccessToken::where('tokenable_id', $user->id)
                ->where('tokenable_type', get_class($user))
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestToken) {
                $oldestToken->delete();
            }
        }

        return $next($request);
    }
}
