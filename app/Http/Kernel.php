<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\SecurityLogging::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        "web" => [
            // \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        "api" => [
            "bindings",
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \App\Http\Middleware\CustomRateLimit::class . ':100,1', // 100 requests per minute
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        "auth"                  => \App\Http\Middleware\Authenticate::class,
        // 'auth.admin' => \App\Http\Middleware\AdminAuthenticate::class,
        // 'auth.teacher' => \App\Http\Middleware\TeacherAuthenticate::class,
        // 'auth.student' => \App\Http\Middleware\StudentAuthenticate::class,
        // 'auth.parent' => \App\Http\Middleware\ParentAuthenticate::class,
        // 'auth.super' => \App\Http\Middleware\SuperAdminMiddleware::class,
        "auth.basic"            =>
        \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        "auth.session"          =>
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        "cache.headers"         => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        "can"                   => \Illuminate\Auth\Middleware\Authorize::class,
        // "guest" => \App\Http\Middleware\RedirectIfAuthenticated::class,
        "password.confirm"      =>
        \Illuminate\Auth\Middleware\RequirePassword::class,
        "signed"                => \Illuminate\Routing\Middleware\ValidateSignature::class,
        "throttle"              => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // "throttle" => TooManyAttemptsException::class,
        "verified"              => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // "jwt.auth" => \App\Http\Middleware\VerifyJWTToken::class,
        "bindings"              => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'checkTokenExpiry'      => \App\Http\Middleware\CheckTokenExpiry::class,
        'role'                  => \App\Http\Middleware\RoleMiddleware::class,
        'check.user.permission' => \App\Http\Middleware\CheckUserPermission::class,
        'admin'                 => \App\Http\Middleware\AdminMiddleware::class,
        'user'                  => \App\Http\Middleware\UserMiddleware::class,
        'rate.limit'            => \App\Http\Middleware\CustomRateLimit::class,
        'security.headers'      => \App\Http\Middleware\SecurityHeaders::class,
        'security.logging'      => \App\Http\Middleware\SecurityLogging::class,
        'check.token.limit'     => \App\Http\Middleware\CheckTokenLimit::class,
        'file.upload.security'  => \App\Http\Middleware\FileUploadSecurity::class,
        'api.versioning'        => \App\Http\Middleware\ApiVersioning::class,
    ];
}
