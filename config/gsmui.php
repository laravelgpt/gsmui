
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GSM-UI Version
    |--------------------------------------------------------------------------
    |
    | This is the version of the GSM-UI component library.
    |
    */

    'version' => '2.0.0',

    /*
    |--------------------------------------------------------------------------
    | GSM-UI Features
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific features of the GSM-UI component library.
    |
    */

    'features' => [
        'sound_effects' => true,
        'animations' => true,
        'grid_patterns' => true,
        'glassmorphism' => true,
        'neon_glows' => true,
        'payment_gateways' => true,
        'multi_language' => false,
        'analytics' => true,
        'notifications' => true,
        'export' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Configure available components.
    |
    */

    'components' => [
        // Core components
        'button' => [
            'type' => 'utility',
            'variants' => ['primary', 'danger', 'ghost'],
            'sizes' => ['sm', 'md', 'lg'],
            'premium' => false,
        ],
        'card' => [
            'type' => 'data-display',
            'variants' => ['default', 'primary', 'glass'],
            'sizes' => ['sm', 'md', 'lg'],
            'premium' => false,
        ],
        'input' => [
            'type' => 'form',
            'variants' => ['default', 'filled', 'outlined'],
            'sizes' => ['sm', 'md', 'lg'],
            'premium' => false,
        ],
        // More components...
    ],

    /*
    |--------------------------------------------------------------------------
    | Premium Components
    |--------------------------------------------------------------------------
    |
    | Components that require a subscription to access.
    |
    */

    'premium_components' => [
        'advanced-datatable',
        'chart-builder',
        'workflow-engine',
        'ai-assistant',
        'voice-commands',
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | Available template categories.
    |
    */

    'templates' => [
        'landing' => [
            'name' => 'Landing Pages',
            'description' => 'High-converting landing pages',
            'premium' => false,
        ],
        'ecommerce' => [
            'name' => 'Ecommerce',
            'description' => 'Online store templates',
            'premium' => true,
        ],
        'saas' => [
            'name' => 'SaaS Dashboards',
            'description' => 'SaaS application dashboards',
            'premium' => false,
        ],
        'admin' => [
            'name' => 'Admin Panels',
            'description' => 'Administrative interfaces',
            'premium' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing
    |--------------------------------------------------------------------------
    |
    | Pricing configuration for subscriptions and purchases.
    |
    */

    'pricing' => [
        'subscription' => [
            'pro_monthly' => 29.99,
            'pro_annual' => 299.99,
            'team_monthly' => 99.99,
            'team_annual' => 999.99,
        ],
        'components' => [
            'basic' => 4.99,
            'premium' => 49.99,
        ],
        'templates' => [
            'single' => 99.99,
            'bundle' => 499.99,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Limits
    |--------------------------------------------------------------------------
    |
    | Usage limits for different subscription tiers.
    |
    */

    'limits' => [
        'free' => [
            'components_per_project' => 10,
            'api_calls_per_day' => 1000,
            'storage_mb' => 100,
        ],
        'pro' => [
            'components_per_project' => 1000,
            'api_calls_per_day' => 100000,
            'storage_mb' => 10000,
        ],
        'team' => [
            'components_per_project' => 10000,
            'api_calls_per_day' => 1000000,
            'storage_mb' => 100000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    |
    | Analytics and tracking configuration.
    |
    */

    'analytics' => [
        'enabled' => true,
        'track_downloads' => true,
        'track_purchases' => true,
        'track_component_usage' => true,
        'retention_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Notification settings.
    |
    */

    'notifications' => [
        'email' => [
            'purchase_confirmation' => true,
            'subscription_upcoming' => true,
            'payment_failed' => true,
            'new_component' => false,
        ],
        'in_app' => [
            'enabled' => true,
            'sound' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | Security settings.
    |
    */

    'security' => [
        'rate_limiting' => true,
        'max_downloads_per_hour' => 100,
        'require_2fa' => false,
        'session_timeout' => 60, // minutes
    ],

];
