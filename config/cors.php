<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths'                    => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods'          => ['*'],

    'allowed_origins'          => [
        env('FRONTEND_URL', 'http://localhost:3000'),
        env('ADMIN_URL', 'http://localhost:3001'),
    ],

    'allowed_origins_patterns' => [
        '/^https:\/\/.*\.yourdomain\.com$/',
    ],

    'allowed_headers'          => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
    ],

    'exposed_headers'          => [
        'X-Total-Count',
        'X-Page-Count',
    ],

    'max_age'                  => 86400, // 24 hours

    'supports_credentials'     => true,

];
