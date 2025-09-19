<?php

return [
    'name' => env('APP_NAME'),
    'env' => env('APP_ENV'),
    'debug' => (bool) env('APP_DEBUG'),
    'url' => env('APP_URL'),
    'timezone' => 'UTC',
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
     * |--------------------------------------------------------------------------
     * | Encryption Key
     * |--------------------------------------------------------------------------
     * |
     * | This key is utilized by Laravel's encryption services and should be set
     * | to a random, 32 character string to ensure that all encrypted values
     * | are secure. You should do this prior to deploying the application.
     * |
     * | TODO: THIS
     */
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),  /* supported: file, cache */
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
