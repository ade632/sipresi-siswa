<?php

return [
    'default' => 'app',

    'projects' => [
        'app' => [
            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/service-account.json')),
                'auto_discovery' => true,
            ],
            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],
            'project_id' => env('FIREBASE_PROJECT_ID'),
        ],
    ],

    'logging' => [
        'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
        'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
    ],
];
