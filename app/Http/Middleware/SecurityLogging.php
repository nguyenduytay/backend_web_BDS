<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime  = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2);

        // Log các hoạt động bảo mật quan trọng
        $this->logSecurityEvent($request, $response, $duration);

        return $response;
    }

    /**
     * Log security events
     */
    private function logSecurityEvent(Request $request, Response $response, float $duration): void
    {
        $user       = $request->user();
        $userId     = $user ? $user->id : 'guest';
        $ip         = $request->ip();
        $userAgent  = $request->userAgent();
        $method     = $request->method();
        $url        = $request->fullUrl();
        $statusCode = $response->getStatusCode();

        // Log failed authentication attempts
        if ($statusCode === 401 || $statusCode === 403) {
            Log::warning('Security: Authentication failed', [
                'user_id'     => $userId,
                'ip'          => $ip,
                'user_agent'  => $userAgent,
                'method'      => $method,
                'url'         => $url,
                'status_code' => $statusCode,
                'timestamp'   => now()->toISOString(),
            ]);
        }

        // Log admin actions
        if ($user && $user->role === 'admin' && in_array($method, ['POST', 'PUT', 'DELETE'])) {
            Log::info('Security: Admin action', [
                'admin_id'    => $userId,
                'ip'          => $ip,
                'method'      => $method,
                'url'         => $url,
                'status_code' => $statusCode,
                'duration_ms' => $duration,
                'timestamp'   => now()->toISOString(),
            ]);
        }

        // Log suspicious activities
        if ($this->isSuspiciousActivity($request, $response)) {
            Log::critical('Security: Suspicious activity detected', [
                'user_id'     => $userId,
                'ip'          => $ip,
                'user_agent'  => $userAgent,
                'method'      => $method,
                'url'         => $url,
                'status_code' => $statusCode,
                'duration_ms' => $duration,
                'timestamp'   => now()->toISOString(),
            ]);
        }
    }

    /**
     * Check for suspicious activities
     */
    private function isSuspiciousActivity(Request $request, Response $response): bool
    {
        // Check for SQL injection patterns
        $suspiciousPatterns = [
            'union select',
            'drop table',
            'delete from',
            'insert into',
            'update set',
            'script>',
            '<script',
            'javascript:',
            'onload=',
            'onerror=',
        ];

        $input = json_encode($request->all());

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($input, $pattern) !== false) {
                return true;
            }
        }

        // Check for rapid requests
        $key   = 'rapid_requests:' . $request->ip();
        $count = cache()->increment($key, 1);
        cache()->put($key, $count, 60); // 1 minute window

        if ($count > 100) { // More than 100 requests per minute
            return true;
        }

        return false;
    }
}
