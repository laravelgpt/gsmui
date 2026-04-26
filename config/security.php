<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */

    'security' => [
        'cors' => [
            'enabled' => true,
            'origins' => ['*'],
            'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'headers' => ['*'],
            'credentials' => true,
        ],
        
        'rate_limiting' => [
            'enabled' => true,
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        
        'session' => [
            'secure' => true,
            'http_only' => true,
            'same_site' => 'strict',
            'lifetime' => 120,
            'encrypt' => true,
        ],
        
        'cookies' => [
            'secure' => true,
            'http_only' => true,
            'same_site' => 'strict',
            'samesite' => 'Strict',
        ],
        
        'headers' => [
            'x_frame_options' => 'DENY',
            'x_content_type_options' => 'nosniff',
            'x_xss_protection' => '1; mode=block',
            'strict_transport_security' => 'max-age=31536000; includeSubDomains; preload',
            'content_security_policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:;",
            'referrer_policy' => 'strict-origin-when-cross-origin',
        ],
        
        'csp' => [
            'enabled' => true,
            'report_only' => false,
            'policies' => [
                'default-src' => ["'self'"],
                'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://cdn.jsdelivr.net', 'https://unpkg.com'],
                'style-src' => ["'self'", "'unsafe-inline'"],
                'img-src' => ["'self'", 'data:', 'https:'],
                'font-src' => ["'self'", 'data:'],
                'connect-src' => ["'self'"],
                'frame-ancestors' => ["'none'"],
                'form-action' => ["'self'"],
                'base-uri' => ["'self'"],
            ],
        ],
    ],
];