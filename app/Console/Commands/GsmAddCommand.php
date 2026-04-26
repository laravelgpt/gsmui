
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GsmAddCommand extends Command
{
    protected $signature = 'gsm:add 
                            {component : The component slug to download}
                            {--token= : Personal Access Token (Sanctum) for authentication}
                            {--local : Use local API (for development)}';

    protected $description = 'Download a GSM UI component to your local project';

    protected $apiBaseUrl = 'http://localhost/api/v1';

    public function handle()
    {
        $componentSlug = $this->argument('component');
        $token = $this->option('token') ?? config('services.gsm.personal_access_token');

        if (!$token) {
            $this->error('❌ No authentication token provided.');
            $this->line('');
            $this->line('Usage:');
            $this->line('  php artisan gsm:add {component} --token={your-token}');
            $this->line('');
            $this->line('Or set GSM_TOKEN in your .env file.');
            return self::FAILURE;
        }

        if ($this->option('local')) {
            $this->apiBaseUrl = 'http://localhost/api/v1';
        }

        $this->info('🌐 Connecting to GSM-UI Marketplace API...');

        try {
            $response = Http::withToken($token)->timeout(30)->get("{$this->apiBaseUrl}/components/{$componentSlug}");

            if ($response->status() === 401) {
                $this->error('❌ Authentication failed. Invalid or expired token.');
                $this->line('Generate a new token at: /dashboard/api');
                return self::FAILURE;
            }

            if ($response->status() === 403) {
                $this->error('❌ Access Denied!');
                $this->line('');
                $this->line('This is a premium component.');
                $this->line('You need an active subscription or must purchase this component separately.');
                $this->line('');
                $this->line('Upgrade at: /dashboard/billing');
                return self::FAILURE;
            }

            if ($response->status() === 404) {
                $this->error('❌ Component not found: ' . $componentSlug);
                $this->line('');
                $this->line('Available components: /components');
                return self::FAILURE;
            }

            if (!$response->successful()) {
                $this->error('❌ API Error: ' . $response->body());
                return self::FAILURE;
            }

            $data = $response->json('data');
            $category = strtolower($data['category'] ?? 'general');
            $localPath = resource_path("views/components/gsm/{$category}");
            $filename = $data['slug'] . '.blade.php';
            $fullPath = "{$localPath}/{$filename}";

            if (!is_dir($localPath)) {
                mkdir($localPath, 0755, true);
                $this->info('📁 Created directory: ' . $localPath);
            }

            if (file_exists($fullPath)) {
                $this->warn('⚠️  Component already exists: ' . $fullPath);
                if (!$this->confirm('Do you want to overwrite it?', false)) {
                    $this->info('Skipped.');
                    return self::SUCCESS;
                }
            }

            file_put_contents($fullPath, $data['code_snippet']);

            $this->info('✅ Component downloaded successfully!');
            $this->line('');
            $this->line('Name:       ' . $data['name']);
            $this->line('Type:       ' . ucfirst($data['type']));
            $this->line('Category:   ' . ucfirst($category));
            $this->line('Path:       ' . $fullPath);
            $this->line('');
            $this->line('💡 Usage in Blade:');
            $this->line('   <x-gsm.' . $category . '.' . $data['slug'] . ' />');
            $this->line('');

            return self::SUCCESS;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error('❌ Connection failed. Is the API server running?');
            $this->line('   Error: ' . $e->getMessage());
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
