
<?php

namespace GSMUI;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use GSMUI\Console\Commands\GSMUIInstallCommand;
use GSMUI\Console\Commands\GSMUIComponentCommand;
use GSMUI\Console\Commands\GSMUIPublishCommand;
use GSMUI\Console\Commands\GSMUITestCommand;

class ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerCommands();
        $this->registerComponents();
        $this->registerEvents();
        $this->registerBladeDirectives();
        $this->publishAssets();
    }

    /**
     * Register services
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/gsmui.php', 'gsmui');
        $this->mergeConfigFrom(__DIR__ . '/../config/payment.php', 'payment');
        $this->mergeConfigFrom(__DIR__ . '/../config/payment_bangladesh.php', 'payment_bangladesh');
        
        $this->registerServices();
        $this->registerSingletons();
        $this->registerHelpers();
    }

    /**
     * Register config files
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/gsmui.php' => config_path('gsmui.php'),
            __DIR__ . '/../config/payment.php' => config_path('payment.php'),
            __DIR__ . '/../config/payment_bangladesh.php' => config_path('payment_bangladesh.php'),
        ], 'gsmui-config');
    }

    /**
     * Register routes
     */
    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/console.php');
    }

    /**
     * Register migrations
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'gsmui-migrations');
    }

    /**
     * Register views
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'gsmui');
        
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/gsmui'),
        ], 'gsmui-views');
    }

    /**
     * Register translations
     */
    protected function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'gsmui');
        
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/gsmui'),
        ], 'gsmui-lang');
    }

    /**
     * Register commands
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GSMUIInstallCommand::class,
                GSMUIComponentCommand::class,
                GSMUIPublishCommand::class,
                GSMUITestCommand::class,
            ]);
        }
    }

    /**
     * Register components
     */
    protected function registerComponents()
    {
        // Auto-register Blade components
        Blade::componentNamespace('GSMUI\\Components\\Blade', 'gsmui');
        Blade::componentNamespace('GSMUI\\Components\\Livewire', 'livewire');
        
        // Register all component views
        $this->registerComponentViews();
    }

    /**
     * Register component views
     */
    protected function registerComponentViews()
    {
        $viewPath = __DIR__ . '/../resources/views/components';
        
        if (is_dir($viewPath)) {
            $this->loadViewsFrom($viewPath, 'components');
        }
    }

    /**
     * Register events
     */
    protected function registerEvents()
    {
        Event::listen(
            \GSMUI\Events\ComponentInstalled::class,
            \GSMUI\Listeners\LogComponentInstallation::class,
        );
    }

    /**
     * Register Blade directives
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('gsmConfig', function ($expression) {
            return "<?php echo config('gsmui.' . $expression); ?>";
        });

        Blade::directive('gsmVersion', function () {
            return "<?php echo config('gsmui.version', '2.0.0'); ?>";
        });

        Blade::directive('gsmAsset', function ($expression) {
            return "<?php echo asset('vendor/gsmui/' . $expression); ?>";
        });

        // Theme color directives
        Blade::directive('electricBlue', function () {
            return "<?php echo 'var(--electric-blue)'; ?>";
        });

        Blade::directive('toxicGreen', function () {
            return "<?php echo 'var(--toxic-green)'; ?>";
        });

        Blade::directive('themeColor', function ($color) {
            return "<?php echo 'var(--' . $color . ')'; ?>";
        });
    }

    /**
     * Publish assets
     */
    protected function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/gsmui'),
        ], 'gsmui-assets');

        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'gsmui-config');
    }

    /**
     * Register services
     */
    protected function registerServices()
    {
        $this->app->singleton(\GSMUI\Services\PaymentService::class, function ($app) {
            return new \GSMUI\Services\PaymentService();
        });

        $this->app->singleton(\GSMUI\Services\ComponentAccessService::class, function ($app) {
            return new \GSMUI\Services\ComponentAccessService();
        });

        $this->app->singleton(\GSMUI\Services\MultiGatewayPaymentService::class, function ($app) {
            return new \GSMUI\Services\MultiGatewayPaymentService();
        });

        $this->app->singleton(\GSMUI\Services\BangladeshPaymentService::class, function ($app) {
            return new \GSMUI\Services\BangladeshPaymentService();
        });

        $this->app->singleton(\GSMUI\Services\GridCNIntegrationService::class, function ($app) {
            return new \GSMUI\Services\GridCNIntegrationService();
        });

        $this->app->singleton(\GSMUI\Services\SoundEffectsService::class, function ($app) {
            return new \GSMUI\Services\SoundEffectsService();
        });
    }

    /**
     * Register singletons
     */
    protected function registerSingletons()
    {
        $this->app->singleton('gsmui', function ($app) {
            return new \GSMUI\Core\GSMUI($app);
        });
    }

    /**
     * Register helpers
     */
    protected function registerHelpers()
    {
        require_once __DIR__ . '/../helpers.php';
    }
}
