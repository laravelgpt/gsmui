
<?php

namespace GSMUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GSMUIPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsmui:publish 
                            {--all : Publish all assets, config, views, and components} 
                            {--config : Publish configuration files} 
                            {--views : Publish view files} 
                            {--components : Publish component stubs} 
                            {--assets : Publish asset files} 
                            {--migrations : Publish migration files} 
                            {--force : Force overwrite of existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish GSM-UI assets, configuration, and components';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📦 Publishing GSM-UI Assets...');
        $this->info('=================================');

        $published = [];

        // Publish all
        if ($this->option('all')) {
            $this->publishConfig();
            $this->publishViews();
            $this->publishComponents();
            $this->publishAssets();
            $this->publishMigrations();
            
            $this->info('\n✅ All assets published successfully!');
            return Command::SUCCESS;
        }

        // Publish specific items
        if ($this->option('config')) {
            $this->publishConfig();
            $published[] = 'config';
        }

        if ($this->option('views')) {
            $this->publishViews();
            $published[] = 'views';
        }

        if ($this->option('components')) {
            $this->publishComponents();
            $published[] = 'components';
        }

        if ($this->option('assets')) {
            $this->publishAssets();
            $published[] = 'assets';
        }

        if ($this->option('migrations')) {
            $this->publishMigrations();
            $published[] = 'migrations';
        }

        // If no options specified, show help
        if (empty($published)) {
            $this->info('\nℹ️  Nothing to publish. Use --all or specify what to publish:');
            $this->info('   --config     Publish configuration files');
            $this->info('   --views      Publish view files');
            $this->info('   --components Publish component stubs');
            $this->info('   --assets     Publish asset files');
            $this->info('   --migrations Publish migration files');
            $this->info('   --all        Publish everything');
            $this->info('   --force      Force overwrite');
            return Command::SUCCESS;
        }

        $this->info('\n✅ Published: ' . implode(', ', $published));
        return Command::SUCCESS;
    }

    /**
     * Publish configuration files
     */
    protected function publishConfig()
    {
        $this->info('\n📄 Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-config',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Configuration published');
    }

    /**
     * Publish view files
     */
    protected function publishViews()
    {
        $this->info('\n📄 Publishing views...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-views',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Views published');
    }

    /**
     * Publish component stubs
     */
    protected function publishComponents()
    {
        $this->info('\n📄 Publishing component stubs...');

        $stubPath = __DIR__ . '/../../../stubs';
        $targetPath = base_path('stubs/gsmui');

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        if (File::exists($stubPath)) {
            $stubs = File::files($stubPath);

            foreach ($stubs as $stub) {
                $target = $targetPath . '/' . $stub->getFilename();
                
                if (!File::exists($target) || $this->option('force')) {
                    File::copy($stub->getPathname(), $target);
                    $this->info('   ✅ ' . $stub->getFilename());
                } else {
                    $this->warn('   ⚠️  Skipped (exists): ' . $stub->getFilename());
                }
            }
        }

        $this->info('✅ Component stubs published');
    }

    /**
     * Publish asset files
     */
    protected function publishAssets()
    {
        $this->info('\n📦 Publishing assets...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-assets',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Assets published');
    }

    /**
     * Publish migration files
     */
    protected function publishMigrations()
    {
        $this->info('\n📄 Publishing migrations...');

        $this->call('vendor:publish', [
            '--provider' => 'GSMUI\ServiceProvider',
            '--tag' => 'gsmui-migrations',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Migrations published');
    }
}
