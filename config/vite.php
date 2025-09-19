<?php

return [
    'manifest' => public_path('build/manifest.json'),
    'dev_server_url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
    'entrypoints' => [
        'resources/js/app.js',
        'resources/css/app.css',
    ],
];
