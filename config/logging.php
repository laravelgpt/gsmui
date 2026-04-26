<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'warning'),
            'ignore_exceptions' => [
                'payment',
                'card',
                'cvv',
                'password',
            ],
            'days' => 30,
            'permission' => 0644,
        ],
        'transactions' => [
            'driver' => 'single',
            'path' => storage_path('logs/transactions.log'),
            'level' => 'info',
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],
    ],
];