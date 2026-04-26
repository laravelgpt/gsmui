
<?php

namespace GSMUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class GSMUIInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsmui:install 
                            {--force : Force installation and overwrite existing files} 
                            {--no-migrations : Skip migration publishing} 
                            {--no-seeders : Skip database seeding} 
                            {--no-assets : Skip asset publishing} 
                            {--no-config : Skip config publishing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install GSM-UI component library with all configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Installing GSM-UI Component Library...');
        $this->info('===========================================');

        // Step 1: Publish configuration
        if (!$this->option('no-config')) {
            $this->publishConfig();
        }

        // Step 2: Publish migrations
        if (!$this->option('no-migrations')) {
            $this->publishMigrations();
        }

        // Step 3: Run migrations
        $this->runMigrations();

        // Step 4: Run seeders (if enabled)
        if (!$this->option('no-seeders')) {
            $this->runSeeders();
        }

        // Step 5: Publish assets
        if (!$this->option('no-assets')) {
            $this->publishAssets();
        }

        // Step 6: Create directories
        $this->createDirectories();

        // Step 7: Generate sample components
        $this->generateSampleComponents();

        // Step 8: Display success message
        $this->displaySuccess();

        return Command::SUCCESS;
    }

    /**
     * Publish configuration files
     */
    protected function publishConfig()
    {
        $this->info('\n📄 Publishing configuration files...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-config',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Configuration files published');
    }

    /**
     * Publish migration files
     */
    protected function publishMigrations()
    {
        $this->info('\n📄 Publishing migration files...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-migrations',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Migration files published');
    }

    /**
     * Run database migrations
     */
    protected function runMigrations()
    {
        $this->info('\n🗄️  Running database migrations...');

        try {
            Artisan::call('migrate', [
                '--force' => $this->option('force'),
            ]);

            $this->info('✅ Database migrations completed');
            $this->info('   Tables created: users, components, templates, purchases, settings, subscriptions, sessions');
        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Run database seeders
     */
    protected function runSeeders()
    {
        $this->info('\n🌱 Running database seeders...');

        try {
            Artisan::call('db:seed', [
                '--class' => 'GSMUIDatabaseSeeder',
                '--force' => $this->option('force'),
            ]);

            $this->info('✅ Database seeded');
            $this->info('   Sample data: 10 components, 5 templates, 3 users');
        } catch (\Exception $e) {
            $this->warn('⚠️  Seeding skipped: ' . $e->getMessage());
        }
    }

    /**
     * Publish asset files
     */
    protected function publishAssets()
    {
        $this->info('\n📦 Publishing asset files...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-assets',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Asset files published');
        $this->info('   Path: public/vendor/gsmui/');
    }

    /**
     * Create necessary directories
     */
    protected function createDirectories()
    {
        $this->info('\n📁 Creating necessary directories...');

        $directories = [
            resource_path('views/vendor/gsmui'),
            resource_path('views/vendor/gsmui/components'),
            resource_path('views/vendor/gsmui/templates'),
            storage_path('app/public/gsmui'),
            public_path('vendor/gsmui'),
            public_path('vendor/gsmui/css'),
            public_path('vendor/gsmui/js'),
            public_path('vendor/gsmui/sounds'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info('   ✅ Created: ' . str_replace(base_path(), '', $directory));
            }
        }

        $this->info('✅ All directories created');
    }

    /**
     * Generate sample components
     */
    protected function generateSampleComponents()
    {
        $this->info('\n🎨 Generating sample components...');

        $sampleComponents = [
            ['name' => 'PrimaryButton', '--category' => 'utilities', '--variant' => 'primary', '--size' => 'md'],
            ['name' => 'DangerButton', '--category' => 'utilities', '--variant' => 'danger', '--size' => 'md'],
            ['name' => 'DataCard', '--category' => 'data-display', '--variant' => 'primary'],
            ['name' => 'StatsCard', '--category' => 'data-display', '--variant' => 'primary'],
            ['name' => 'InputField', '--category' => 'forms', '--variant' => 'default'],
            ['name' => 'SubmitButton', '--category' => 'forms', '--variant' => 'primary', '--size' => 'lg'],
        ];

        foreach ($sampleComponents as $component) {
            try {
                Artisan::call('gsmui:component', $component);
                $this->info('   ✅ Created: ' . $component['name']);
            } catch (\Exception $e) {
                $this->warn('   ⚠️  Skipped: ' . $component['name']);
            }
        }

        $this->info('✅ Sample components generated');
    }

    /**
     * Display success message
     */
    protected function displaySuccess()
    {
        $this->info('\n' . str_repeat('═', 50));
        $this->info('🎉 GSM-UI Installation Complete!');
        $this->info(str_repeat('═', 50));

        $this->info('\n📋 Next Steps:');
        $this->info('   1. Configure your settings in config/gsmui.php');
        $this->info('   2. Configure payment gateways in config/payment.php');
        $this->info('   3. Run: php artisan gsmui:component YourComponent');
        $this->info('   4. Run: php artisan gsmui:publish --all');

        $this->info('\n📚 Documentation:');
        $this->info('   - Component Usage: https://docs.gsm-ui.com/components');
        $this->info('   - API Reference: https://docs.gsm-ui.com/api');
        $this->info('   - Configuration: https://docs.gsm-ui.com/config');

        $this->info('\n💡 Quick Start:');
        $this->info('   // In your Blade view:');
        $this->info('   <x-gsmui::components.utilities.primary-button');
        $this->info('       label="Click Me"');
        $this->info('       variant="primary"');
        $this->info('       size="md"');
        $this->info('   />');

        $this->info('\n🚀 You\'re ready to build!');
        $this->info(str_repeat('═', 50) . "\n");
    }
}
