<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersioning
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentVersion     = $this->getCurrentApiVersion($request);
        $supportedVersions  = config('api.supported_versions', ['v1']);
        $latestVersion      = config('api.latest_version', 'v1');
        $deprecatedVersions = config('api.deprecated_versions', []);

        // Kiểm tra version có được hỗ trợ không
        if (! in_array($currentVersion, $supportedVersions)) {
            return response()->json([
                'error'              => 'API version not supported',
                'supported_versions' => $supportedVersions,
                'latest_version'     => $latestVersion,
            ], 400);
        }

        // Kiểm tra version có bị deprecated không
        if (in_array($currentVersion, $deprecatedVersions)) {
            $response = $next($request);

            $response->headers->set('X-API-Version', $currentVersion);
            $response->headers->set('X-API-Deprecated', 'true');
            $response->headers->set('X-API-Sunset-Date', config('api.deprecation_dates.' . $currentVersion, '2024-12-31'));
            $response->headers->set('X-API-Latest-Version', $latestVersion);

            return $response;
        }

        $response = $next($request);
        $response->headers->set('X-API-Version', $currentVersion);
        $response->headers->set('X-API-Latest-Version', $latestVersion);

        return $response;
    }

    /**
     * Get current API version from request
     */
    private function getCurrentApiVersion(Request $request): string
    {
        // Kiểm tra version trong header
        $version = $request->header('X-API-Version');
        if ($version) {
            return $version;
        }

        // Kiểm tra version trong URL path
        $path = $request->path();
        if (preg_match('/^api\/(v\d+)\//', $path, $matches)) {
            return $matches[1];
        }

        // Kiểm tra version trong query parameter
        $version = $request->query('version');
        if ($version) {
            return $version;
        }

        // Default version
        return config('api.default_version', 'v1');
    }
}
