
<?php

namespace GSMUI\Console;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services
     */
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * Register commands
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register console commands
     */
    protected function registerCommands()
    {
        $this->commands([
            Commands\GSMUIInstallCommand::class,
            Commands\GSMUIComponentCommand::class,
            Commands\GSMUIPublishCommand::class,
            Commands\GSMUITestCommand::class,
        ]);
    }
}
