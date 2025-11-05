<?php
namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CustomRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return ApiResponse::error(
                'Quá nhiều yêu cầu. Vui lòng thử lại sau ' . $decayMinutes . ' phút.',
                null,
                429
            );
        }

        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));

        return $next($request);
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $user = $request->user();
        $ip   = $request->ip();

        if ($user) {
            return 'rate_limit:' . $user->id . ':' . $request->route()->getName();
        }

        return 'rate_limit:' . $ip . ':' . $request->route()->getName();
    }
}
