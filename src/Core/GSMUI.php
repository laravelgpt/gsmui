
<?php

namespace GSMUI\Core;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class GSMUI
{
    /**
     * The application instance
     */
    protected $app;

    /**
     * Create a new GSM-UI instance
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the version
     */
    public function version()
    {
        return config('gsmui.version', '2.0.0');
    }

    /**
     * Check if user can access premium features
     */
    public function userCanAccessPremium($user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        return $user->has_active_subscription ?? false;
    }

    /**
     * Get available payment gateways
     */
    public function availableGateways()
    {
        $gateways = config('payment.gateways', []);
        
        return array_filter($gateways, function ($gateway) {
            return $gateway['enabled'] ?? false;
        });
    }

    /**
     * Get Bangladesh payment gateways
     */
    public function bangladeshGateways()
    {
        $gateways = config('payment_bangladesh.bangladesh', []);
        
        return array_filter($gateways, function ($gateway) {
            return $gateway['enabled'] ?? false;
        });
    }

    /**
     * Get component categories
     */
    public function componentCategories()
    {
        return [
            'data-display' => 'Data Display',
            'forms' => 'Forms',
            'navigation' => 'Navigation',
            'feedback' => 'Feedback',
            'layout' => 'Layout',
            'media' => 'Media',
            'utilities' => 'Utilities',
        ];
    }

    /**
     * Get template categories
     */
    public function templateCategories()
    {
        return [
            'landing' => 'Landing Pages',
            'ecommerce' => 'Ecommerce',
            'saas' => 'SaaS Dashboards',
            'admin' => 'Admin Panels',
            'marketing' => 'Marketing',
            'portfolio' => 'Portfolio',
            'blog' => 'Blog',
            'documentation' => 'Documentation',
            'coming-soon' => 'Coming Soon',
            'error' => 'Error Pages',
        ];
    }

    /**
     * Format price
     */
    public function formatPrice($amount, $currency = 'USD')
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

    /**
     * Check if feature is enabled
     */
    public function isFeatureEnabled($feature)
    {
        return config('gsmui.features.' . $feature, true);
    }

    /**
     * Get theme colors
     */
    public function themeColors()
    {
        return [
            'electric-blue' => '#00D4FF',
            'toxic-green' => '#39FF14',
            'indigo' => '#6366F1',
            'deep-space' => '#0B0F19',
        ];
    }

    /**
     * Get user's purchased components
     */
    public function getUserPurchases($user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return collect();
        }

        return $user->purchases()
            ->with('purchasable')
            ->where('payment_status', 'completed')
            ->get();
    }

    /**
     * Get user's active subscription
     */
    public function getUserSubscription($user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return null;
        }

        return $user->subscription('pro');
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription($user = null)
    {
        return $this->userCanAccessPremium($user);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total_components' => 400,
            'total_templates' => 112,
            'total_gateways' => 80,
            'total_users' => \App\Models\User::count(),
            'total_purchases' => \App\Models\Purchase::where('payment_status', 'completed')->count(),
            'total_revenue' => \App\Models\Purchase::where('payment_status', 'completed')->sum('amount'),
        ];
    }

    /**
     * Check if component exists
     */
    public function componentExists($name)
    {
        return view()->exists('components.blade.' . $name) ||
               class_exists('App\Components\Livewire\Volt\\' . $name) ||
               file_exists(app_path('Components/React/components/' . $name . '.jsx')) ||
               file_exists(app_path('Components/Vue/components/' . $name . '.vue'));
    }

    /**
     * Register a new component
     */
    public function registerComponent($name, $config = [])
    {
        // Logic to register component
        return true;
    }

    /**
     * Get component configuration
     */
    public function getComponentConfig($name)
    {
        return config('gsmui.components.' . $name, []);
    }

    /**
     * Set component configuration
     */
    public function setComponentConfig($name, $config)
    {
        config(['gsmui.components.' . $name => $config]);
        
        return $this;
    }

    /**
     * Get all components
     */
    public function getAllComponents()
    {
        return config('gsmui.components', []);
    }

    /**
     * Get premium components
     */
    public function getPremiumComponents()
    {
        return config('gsmui.premium_components', []);
    }

    /**
     * Get free components
     */
    public function getFreeComponents()
    {
        return array_diff(
            array_keys(config('gsmui.components', [])),
            config('gsmui.premium_components', [])
        );
    }

    /**
     * Check if user can download component
     */
    public function canDownloadComponent($component, $user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return !is_premium_component($component);
        }

        return app(\GSMUI\Services\ComponentAccessService::class)
            ->canAccessComponent($user, $component);
    }

    /**
     * Download component
     */
    public function downloadComponent($component, $user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return [
                'success' => false,
                'error' => 'Authentication required',
            ];
        }

        $componentModel = \App\Models\Component::where('slug', $component)->first();

        if (!$componentModel) {
            return [
                'success' => false,
                'error' => 'Component not found',
            ];
        }

        return app(\GSMUI\Services\ComponentAccessService::class)
            ->downloadComponentForCLI($user, $componentModel);
    }

    /**
     * Purchase component
     */
    public function purchaseComponent($component, $user, $paymentMethod = null)
    {
        $componentModel = \App\Models\Component::where('slug', $component)->first();

        if (!$componentModel) {
            return [
                'success' => false,
                'error' => 'Component not found',
            ];
        }

        return app(\GSMUI\Services\PaymentService::class)
            ->purchaseItem($user, 'component', $componentModel->id, $componentModel->price, $paymentMethod);
    }
}
