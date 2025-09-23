<?php

use Illuminate\Support\Str;

return [
    /* "file", "cookie", "database", "apc",  "memcached", "redis", "dynamodb", "array" */
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', true),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    /* Affects: "apc", "dynamodb", "memcached", "redis" */
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),
    'path' => env('SESSION_PATH', '/'),
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
     * - lax: permit secure cross-site requests.
     * - strict: no
     * - none: no clue ...
     * - null: no same-site attribute is set.
     */
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
    /* accepted only if secure and Same-Site is none. */
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];
