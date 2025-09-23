<?php

return [
    /* https://inertiajs.com/server-side-rendering */
    'ssr' => [
        'enabled' => true,
        'url' => 'http://127.0.0.1:13714',
        // 'bundle' => base_path('bootstrap/ssr/ssr.mjs'),
    ],
    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('js/pages'),
        ],
        'page_extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],
    ],
];
