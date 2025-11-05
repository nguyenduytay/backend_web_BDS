<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains API-related configurations including versioning,
    | deprecation, and other API management settings.
    |
    */

    'default_version'     => env('API_DEFAULT_VERSION', 'v1'),
    'latest_version'      => env('API_LATEST_VERSION', 'v1'),
    'supported_versions'  => explode(',', env('API_SUPPORTED_VERSIONS', 'v1')),
    'deprecated_versions' => explode(',', env('API_DEPRECATED_VERSIONS', '')),

    'deprecation_dates'   => [
        // 'v1' => '2024-12-31',
    ],

    'versioning_strategy' => env('API_VERSIONING_STRATEGY', 'header'), // header, url, query

    'rate_limits'         => [
        'v1' => [
            'default' => 100, // requests per minute
            'auth'    => 10,
            'admin'   => 200,
        ],
    ],

    'response_headers'    => [
        'X-API-Version',
        'X-API-Latest-Version',
        'X-API-Deprecated',
        'X-API-Sunset-Date',
    ],

    'documentation'       => [
        'enabled'  => env('API_DOCS_ENABLED', true),
        'url'      => env('API_DOCS_URL', '/api/docs'),
        'versions' => [
            'v1' => [
                'status'      => 'stable',
                'deprecated'  => false,
                'sunset_date' => null,
            ],
        ],
    ],
];
