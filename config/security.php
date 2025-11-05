<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configurations for the application.
    |
    */

    'password'      => [
        'min_length'        => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers'   => true,
        'require_symbols'   => true,
        'max_attempts'      => 5,
        'lockout_duration'  => 15, // minutes
    ],

    'rate_limiting' => [
        'auth' => [
            'login'           => ['max' => 10, 'decay' => 1], // 10 per minute
            'register'        => ['max' => 5, 'decay' => 1],  // 5 per minute
            'forgot_password' => ['max' => 3, 'decay' => 5],  // 3 per 5 minutes
        ],
        'api'  => [
            'default' => ['max' => 100, 'decay' => 1], // 100 per minute
            'admin'   => ['max' => 200, 'decay' => 1], // 200 per minute
        ],
    ],

    'headers'       => [
        'x_content_type_options' => 'nosniff',
        'x_frame_options'        => 'DENY',
        'x_xss_protection'       => '1; mode=block',
        'referrer_policy'        => 'strict-origin-when-cross-origin',
        'permissions_policy'     => 'geolocation=(), microphone=(), camera=()',
        'hsts_max_age'           => 31536000, // 1 year
    ],

    'logging'       => [
        'security_events'     => true,
        'failed_auth'         => true,
        'admin_actions'       => true,
        'suspicious_activity' => true,
    ],

    'cors'          => [
        'allowed_origins'      => [
            env('FRONTEND_URL', 'http://localhost:3000'),
            env('ADMIN_URL', 'http://localhost:3001'),
        ],
        'allowed_methods'      => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers'      => [
            'Accept',
            'Authorization',
            'Content-Type',
            'X-Requested-With',
            'X-CSRF-TOKEN',
        ],
        'max_age'              => 86400, // 24 hours
        'supports_credentials' => true,
    ],

    'token'         => [
        'expiration'          => 120, // minutes
        'refresh_threshold'   => 30,  // minutes before expiration
        'max_tokens_per_user' => 5,
    ],
];
