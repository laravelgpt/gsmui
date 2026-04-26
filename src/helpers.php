
<?php

use GSMUI\Services\SoundEffectsService;

if (!function_exists('gsmui')) {
    /**
     * Get the GSM-UI instance
     *
     * @return \GSMUI\Core\GSMUI
     */
    function gsmui()
    {
        return app('gsmui');
    }
}

if (!function_exists('gsmui_config')) {
    /**
     * Get GSM-UI configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function gsmui_config($key, $default = null)
    {
        return config('gsmui.' . $key, $default);
    }
}

if (!function_exists('gsmui_version')) {
    /**
     * Get GSM-UI version
     *
     * @return string
     */
    function gsmui_version()
    {
        return config('gsmui.version', '2.0.0');
    }
}

if (!function_exists('gsmui_asset')) {
    /**
     * Get GSM-UI asset URL
     *
     * @param string $path
     * @return string
     */
    function gsmui_asset($path)
    {
        return asset('vendor/gsmui/' . $path);
    }
}

if (!function_exists('gsmui_sound')) {
    /**
     * Get sound effects service instance
     *
     * @return \GSMUI\Services\SoundEffectsService
     */
    function gsmui_sound()
    {
        return app(SoundEffectsService::class);
    }
}

if (!function_exists('gsmui_play_sound')) {
    /**
     * Play a sound effect
     *
     * @param string $sound
     * @param array $options
     * @return array
     */
    function gsmui_play_sound($sound, $options = [])
    {
        return gsmui_sound()->play($sound, $options);
    }
}

if (!function_exists('gsmui_component')) {
    /**
     * Render a GSM-UI component
     *
     * @param string $name
     * @param array $props
     * @return string
     */
    function gsmui_component($name, $props = [])
    {
        return view('components.' . $name, $props)->render();
    }
}

if (!function_exists('electric_blue')) {
    /**
     * Get electric blue color
     *
     * @return string
     */
    function electric_blue()
    {
        return 'var(--electric-blue)';
    }
}

if (!function_exists('toxic_green')) {
    /**
     * Get toxic green color
     *
     * @return string
     */
    function toxic_green()
    {
        return 'var(--toxic-green)';
    }
}

if (!function_exists('theme_color')) {
    /**
     * Get theme color
     *
     * @param string $color
     * @return string
     */
    function theme_color($color)
    {
        return 'var(--' . $color . ')';
    }
}

if (!function_exists('is_premium_component')) {
    /**
     * Check if a component is premium
     *
     * @param string $component
     * @return bool
     */
    function is_premium_component($component)
    {
        // Logic to check if component requires subscription
        $premiumComponents = config('gsmui.premium_components', []);
        return in_array($component, $premiumComponents);
    }
}

if (!function_exists('can_access_component')) {
    /**
     * Check if user can access a component
     *
     * @param string $component
     * @param \App\Models\User|null $user
     * @return bool
     */
    function can_access_component($component, $user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return !is_premium_component($component);
        }

        return app(\GSMUI\Services\ComponentAccessService::class)
            ->canAccessComponent($user, $component);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency for display
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    function format_currency($amount, $currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'BDT' => '৳',
            'INR' => '₹',
            'NGN' => '₦',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('gsmui_route')) {
    /**
     * Generate GSM-UI route URL
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    function gsmui_route($name, $parameters = [])
    {
        return route('gsmui.' . $name, $parameters);
    }
}

if (!function_exists('component_icon')) {
    /**
     * Get component icon HTML
     *
     * @param string $name
     * @param string $class
     * @return string
     */
    function component_icon($name, $class = 'h-4 w-4')
    {
        return '<svg class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
        </svg>';
    }
}
