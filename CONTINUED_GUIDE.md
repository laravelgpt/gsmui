
# GSM-UI Laravel Package - Continued Implementation Guide

## Phase 9: Advanced Component Development (45 minutes)

### Step 9.1: Create Base Component Interface
```php
// app/Components/Contracts/ComponentInterface.php

namespace App\Components\Contracts;

interface ComponentInterface
{
    public function render(): string;
    public function getProps(): array;
    public function getVariants(): array;
    public function getSizes(): array;
}
```

### Step 9.2: Implement Base Component
```php
// app/Components/Shared/BaseComponent.php

namespace App\Components\Shared;

use App\Components\Contracts\ComponentInterface;

abstract class BaseComponent implements ComponentInterface
{
    protected string $name;
    protected array $props = [];
    protected array $variants = ['primary', 'secondary', 'ghost', 'danger', 'success'];
    protected array $sizes = ['sm', 'md', 'lg', 'xl'];
    
    public function __construct(array $props = [])
    {
        $this->props = $props;
    }
    
    public function render(): string
    {
        return view($this->getViewPath(), $this->getViewData())->render();
    }
    
    abstract protected function getViewPath(): string;
    
    abstract protected function getViewData(): array;
    
    public function getProps(): array
    {
        return $this->props;
    }
    
    public function getVariants(): array
    {
        return $this->variants;
    }
    
    public function getSizes(): array
    {
        return $this->sizes;
    }
    
    protected function mergeClass(string $base, string $additional): string
    {
        return trim($base . ' ' . $additional);
    }
    
    protected function getVariantClass(): string
    {
        $variant = $this->props['variant'] ?? 'primary';
        return "gsm-variant-{$variant}";
    }
    
    protected function getSizeClass(): string
    {
        $size = $this->props['size'] ?? 'md';
        return "gsm-size-{$size}";
    }
}
```

### Step 9.3: Create Component Registry
```php
// app/Components/Shared/ComponentRegistry.php

namespace App\Components\Shared;

use Illuminate\Support\Collection;

class ComponentRegistry
{
    protected Collection $components;
    
    public function __construct()
    {
        $this->components = collect();
    }
    
    public function register(string $name, string $class): self
    {
        $this->components->put($name, $class);
        return $this;
    }
    
    public function get(string $name): ?string
    {
        return $this->components->get($name);
    }
    
    public function has(string $name): bool
    {
        return $this->components->has($name);
    }
    
    public function all(): Collection
    {
        return $this->components;
    }
    
    public function byCategory(string $category): Collection
    {
        return $this->components->filter(function ($class) use ($category) {
            return strpos($class, "\\" . ucfirst($category) . "\\") !== false;
        });
    }
    
    public function toArray(): array
    {
        return $this->components->toArray();
    }
}
```

### Step 9.4: Service Provider Registration
```php
// app/Providers/GSMUIComponentServiceProvider.php

namespace App\Providers;

use App\Components\Shared\ComponentRegistry;
use Illuminate\Support\ServiceProvider;

class GSMUIComponentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ComponentRegistry::class, function ($app) {
            $registry = new ComponentRegistry();
            
            // Register UI Components
            $registry->register('button', \App\Components\Blade\Class\Button::class);
            $registry->register('card', \App\Components\Blade\Class\Card::class);
            $registry->register('modal', \App\Components\Blade\Class\Modal::class);
            
            // Register Form Components
            $registry->register('input', \App\Components\Blade\Class\Input::class);
            $registry->register('select', \App\Components\Blade\Class\Select::class);
            $registry->register('textarea', \App\Components\Blade\Class\Textarea::class);
            
            // Add more components here
            
            return $registry;
        });
    }
    
    public function boot(): void
    {
        // Publish component views
        $this->publishes([
            __DIR__ . '/../../resources/views/components' => resource_path('views/components'),
        ], 'gsmui-components');
    }
}
```

### Step 9.5: Register Service Provider
```php
// config/app.php

'providers' => [
    // Other providers...
    App\Providers\GSMUIComponentServiceProvider::class,
],
```

### Step 9.6: Facade for Easy Access
```php
// app/Facades/GSMUI.php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GSMUI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gsmui';
    }
}
```

### Step 9.7: Core Class
```php
// app/Core/GSMUI.php

namespace App\Core;

use App\Components\Shared\ComponentRegistry;

class GSMUI
{
    protected ComponentRegistry $registry;
    
    public function __construct(ComponentRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    public function component(string $name, array $props = []): string
    {
        $class = $this->registry->get($name);
        
        if (!$class) {
            throw new \Exception("Component [{$name}] not registered.");
        }
        
        return (new $class($props))->render();
    }
    
    public function hasComponent(string $name): bool
    {
        return $this->registry->has($name);
    }
    
    public function allComponents(): array
    {
        return $this->registry->toArray();
    }
    
    public function componentsByCategory(string $category): array
    {
        return $this->registry->byCategory($category)->toArray();
    }
    
    public function version(): string
    {
        return config('gsmui.version', '2.0.0');
    }
    
    public function config(string $key, $default = null)
    {
        return config("gsmui.{$key}", $default);
    }
}
```

### Step 9.8: Register Core Class
```php
// AppServiceProvider.php

public function register(): void
{
    $this->app->singleton('gsmui', function ($app) {
        return new \App\Core\GSMUI(
            $app->make(\App\Components\Shared\ComponentRegistry::class)
        );
    });
}
```

### Step 9.9: Helper Functions
```php
// src/helpers.php

if (!function_exists('gsmui')) {
    function gsmui(...$args)
    {
        return app('gsmui')->component(...$args);
    }
}

if (!function_exists('gsmConfig')) {
    function gsmConfig(string $key, $default = null)
    {
        return config("gsmui.{$key}", $default);
    }
}

if (!function_exists('gsmVersion')) {
    function gsmVersion(): string
    {
        return app('gsmui')->version();
    }
}

if (!function_exists('gsmAsset')) {
    function gsmAsset(string $path): string
    {
        return asset('vendor/gsmui/' . ltrim($path, '/'));
    }
}
```

### Step 9.10: Usage Examples
```php
// In controllers
use App\Core\GSMUI;

public function index(GSMUI $gsmui)
{
    $button = $gsmui->component('button', [
        'label' => 'Click Me',
        'variant' => 'primary',
        'size' => 'md'
    ]);
    
    return view('welcome', compact('button'));
}

// In Blade templates
{{ gsmui('button', ['label' => 'Submit', 'variant' => 'primary']) }}

// Or using helper
@php
    $button = gsmui('card', ['title' => 'Dashboard'])
@endphp
{!! $button !!}
```

---

## Phase 10: API Enhancement (30 minutes)

### Step 10.1: API Resource Classes
```php
// app/Http/Resources/ComponentResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComponentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'variant' => $this->variant,
            'size' => $this->size,
            'price' => $this->price,
            'currency' => $this->currency,
            'preview_url' => $this->preview_url,
            'download_url' => route('api.components.download', $this->id),
            'stacks' => $this->stacks,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
```

### Step 10.2: Enhanced API Controller
```php
// app/Http/Controllers/Api/V1/ComponentController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Models\Component;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function index(Request $request)
    {
        $components = Component::query()
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(20);
        
        return ComponentResource::collection($components)
            ->additional([
                'meta' => [
                    'total' => $components->total(),
                    'per_page' => $components->perPage(),
                    'current_page' => $components->currentPage(),
                ],
            ]);
    }
    
    public function show(Component $component)
    {
        return new ComponentResource($component);
    }
    
    public function download(Component $component)
    {
        // Log download
        activity()->performedOn($component)
            ->causedBy(auth()->user())
            ->log('downloaded_component');
        
        // Return download response
        return response()->json([
            'download_url' => $component->download_url,
            'expires_at' => now()->addMinutes(15)->toISOString(),
        ]);
    }
}
```

### Step 10.3: API Routes
```php
// routes/api.php

use App\Http\Controllers\Api\V1\ComponentController;
use App\Http\Controllers\Api\V1\TemplateController;
use App\Http\Controllers\Api\V1\PurchaseController;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('components', ComponentController::class);
        Route::apiResource('templates', TemplateController::class);
        Route::apiResource('purchases', PurchaseController::class);
        
        Route::post('components/{component}/download', 
            [ComponentController::class, 'download']);
    });
    
    // Public endpoints
    Route::get('components', [ComponentController::class, 'index']);
    Route::get('templates', [TemplateController::class, 'index']);
});
```

### Step 10.4: API Documentation
```php
// routes/api.php (add Swagger/OpenAPI comments)

/**
 * @OA\Info(
 *     title="GSM-UI API",
 *     version="2.0.0",
 *     description="API for GSM-UI Component Marketplace"
 * )
 * @OA\Server(
 *     url="http://localhost/api/v1",
 *     description="Local Development Server"
 * )
 */
```

### Step 10.5: Rate Limiting
```php
// app/Http/Kernel.php

'api' => [
    'throttle:60,1',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### Step 10.6: API Testing
```bash
# Test API endpoints
php artisan test --filter=Api

# Test with Postman/curl
curl -X GET http://localhost/api/v1/components \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Phase 11: Deployment Pipeline (60 minutes)

### Step 11.1: GitHub Actions Setup
```yaml
# .github/workflows/deploy.yml

name: Deploy to Production

on:
  push:
    branches: [master]
  pull_request:
    branches: [master]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: testing
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, pdo, pdo_mysql
          coverage: xdebug
        
      - name: Install Dependencies
        run: composer install --prefer-dist --no-interaction
        
      - name: Copy .env
        run: cp .env.example .env
        
      - name: Generate Key
        run: php artisan key:generate
        
      - name: Directory Permissions
        run: chmod -R 777 storage bootstrap/cache
        
      - name: Create Database
        run: |
          mysql -u root -ppassword -e 'CREATE DATABASE IF NOT EXISTS testing;'
        
      - name: Execute Tests
        env:
          DB_CONNECTION: mysql
          DB_DATABASE: testing
        run: |
          php artisan migrate --seed
          php artisan test
      
      - name: Security Audit
        run: php security_audit.php
```

### Step 11.2: Deployment Script
```bash
#!/bin/bash
# deploy.sh

set -e

echo "🚀 Starting deployment..."

# Configuration
SERVER="user@production-server"
APP_DIR="/var/www/gsmui"
BRANCH="master"

# Pull latest code
echo "📥 Pulling latest code..."
ssh $SERVER "
  cd $APP_DIR
  git pull origin $BRANCH
"

# Install dependencies
echo "📦 Installing dependencies..."
ssh $SERVER "
  cd $APP_DIR
  composer install --optimize-autoloader --no-dev
  npm install --production
  npm run build
"

# Run migrations
echo "🔄 Running migrations..."
ssh $SERVER "
  cd $APP_DIR
  php artisan migrate --force --seed
"

# Clear cache
echo "🧹 Clearing cache..."
ssh $SERVER "
  cd $APP_DIR
  php artisan optimize:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
"

# Run tests
echo "✅ Running tests..."
ssh $SERVER "
  cd $APP_DIR
  php artisan gsmui:test
  php security_audit.php
"

# Restart services
echo "🔄 Restarting services..."
ssh $SERVER "
  sudo systemctl restart php8.2-fpm
  sudo systemctl restart nginx
"

echo "🎉 Deployment complete!"
```

### Step 11.3: Environment Variables
```bash
# Production .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gsmui.yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=gsmui_prod
DB_USERNAME=gsmui_user
DB_PASSWORD=secure_password

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailgun
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=mailgun_password

# Payment
STRIPE_KEY=pk_live_your_key
STRIPE_SECRET=sk_live_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook
```

### Step 11.4: Monitoring Setup
```yaml
# .github/workflows/monitor.yml

name: Monitor Application

on:
  schedule:
    - cron: '0 */2 * * *'  # Every 2 hours
  workflow_dispatch:

jobs:
  monitor:
    runs-on: ubuntu-latest
    
    steps:
      - name: Health Check
        run: |
          curl -f https://gsmui.yourdomain.com/health || exit 1
      
      - name: Check Logs
        run: |
          ssh user@server 'tail -100 /var/www/gsmui/storage/logs/laravel.log' | \
            grep -E "ERROR|CRITICAL" && exit 1 || exit 0
      
      - name: Security Scan
        run: |
          ssh user@server 'cd /var/www/gsmui && php security_audit.php' | \
            grep -E "FAILED|ERROR" && exit 1 || exit 0
```

### Step 11.5: Rollback Plan
```bash
#!/bin/bash
# rollback.sh

set -e

SERVER="user@production-server"
APP_DIR="/var/www/gsmui"

echo "⚠️  Rolling back..."

ssh $SERVER "
  cd $APP_DIR
  
  # Get previous commit
  PREVIOUS_COMMIT=\$(git log --oneline | grep -A1 'HEAD' | tail -1 | awk '{print \$1}')
  
  # Rollback
  git reset --hard \$PREVIOUS_COMMIT
  
  # Rollback database
  php artisan migrate:rollback --step=1
  
  # Clear cache
  php artisan optimize:clear
  
  echo 'Rollback to commit: \$PREVIOUS_COMMIT'
"

echo "🔄 Restarting services..."
ssh $SERVER "
  sudo systemctl restart php8.2-fpm
  sudo systemctl restart nginx
"

echo "✅ Rollback complete!"
```

---

## Phase 12: Production Optimization (30 minutes)

### Step 12.1: OPcache Configuration
```ini
; /etc/php/8.2/fpm/conf.d/10-opcache.ini

zend_extension=opcache.so
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=32
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.enable_cli=1
```

### Step 12:2: Nginx Optimization
```nginx
# /etc/nginx/sites-available/gsmui

server {
    listen 80;
    server_name gsmui.yourdomain.com;
    root /var/www/gsmui/public;
    
    add_header X-Frame-Options "DENY";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;
    }
    
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires max;
        log_not_found off;
        add_header Cache-Control "public, immutable";
    }
    
    location ~* \.(env|log|htaccess|ini|lock|sql|git) {
        deny all;
    }
    
    location ~ /\. {
        deny all;
    }
}
```

### Step 12.3: Redis Configuration
```conf
# /etc/redis/redis.conf

maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
rdbcompression yes
rdbchecksum yes
```

### Step 12.4: Queue Worker
```bash
# Start queue worker
php artisan queue:work --queue=high,default --sleep=3 --tries=3 --max-time=3600

# Or use supervisor
# /etc/supervisor/conf.d/gsmui-worker.conf

[program:gsmui-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/gsmui/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/gsmui/storage/logs/worker.log
stopwaitsecs=3600
```

### Step 12.5: Database Indexing
```php
// database/migrations/2024_01_02_000000_add_indexes.php

Schema::table('components', function (Blueprint $table) {
    $table->index('category');
    $table->index('variant');
    $table->index('price');
    $table->index('created_at');
});

Schema::table('purchases', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('component_id');
    $table->index('payment_status');
    $table->index('created_at');
});
```

### Step 12.6: Caching Strategy
```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\Cache;
use App\Models\Component;

public function boot(): void
{
    // Cache component catalog
    Cache::remember('components.catalog', 3600, function () {
        return Component::with('media')
            ->where('is_published', true)
            ->orderBy('name')
            ->get();
    });
    
    // Cache configuration
    Cache::remember('config.payment', 7200, function () {
        return PaymentGateway::all()->pluck('config', 'name');
    });
}
```

### Step 12.7: CDN Integration
```php
// config/filesystems.php

'disks' => [
    'cdn' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_CDN_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
    ],
],
```

### Step 12.8: Performance Testing
```bash
# Load testing with Apache Bench
ab -n 1000 -c 100 https://gsmui.yourdomain.com/

ab -n 500 -c 50 https://gsmui.yourdomain.com/api/v1/components

# Monitor during test
watch -n 1 'tail -20 /var/www/gsmui/storage/logs/laravel.log'
```

---

## Phase 13: Security Hardening (45 minutes)

### Step 13.1: File Permissions
```bash
# Set proper permissions
chown -R www-data:www-data /var/www/gsmui
find /var/www/gsmuri/bootstrap /var/www/gsmuri/storage -type d -exec chmod 775 {} \;
find /var/www/gsmuri/bootstrap /var/www/gsmuri/storage -type f -exec chmod 664 {} \;
chmod -R 775 /var/www/gsmuri/storage/logs
```

### Step 13.2: PHP Hardening
```ini
; /etc/php/8.2/fpm/php.ini

expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
allow_url_fopen = Off
allow_url_include = Off
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
session.cookie_samesite = "Strict"
cgi.fix_pathinfo = 0
```

### Step 13.3: Database Security
```sql
-- Create dedicated user with limited permissions
CREATE USER 'gsmui_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON gsmui.* TO 'gsmui_user'@'localhost';
FLUSH PRIVILEGES;

-- Remove root remote access
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
FLUSH PRIVILEGES;
```

### Step 13.4: Fail2Ban
```ini
# /etc/fail2ban/jail.local

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
port = http,https
logpath = /var/log/nginx/error.log
maxretry = 3
bantime = 3600

[nginx-botsearch]
enabled = true
port = http,https
filter = nginx-botsearch
logpath = /var/log/nginx/access.log
maxretry = 5
bantime = 86400
```

### Step 13.5: SSL/TLS Configuration
```nginx
# /etc/nginx/sites-available/gsmui (HTTPS)

server {
    listen 443 ssl http2;
    server_name gsmui.yourdomain.com;
    
    ssl_certificate /etc/letsencrypt/live/gsmui.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/gsmui.yourdomain.com/privkey.pem;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    
    # HSTS
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # ... rest of configuration
}

server {
    listen 80;
    server_name gsmui.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### Step 13.6: Security Headers
```nginx
# Additional security headers
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self';" always;
```

### Step 13.7: Security Auditing
```bash
# Install security tools
composer require enlightn/security-checker

# Run security checks
composer security-checker security:check

# Check for known vulnerabilities
php artisan security:audit
```

### Step 13.8: Intrusion Detection
```bash
# Install AIDE
apt-get install aide

# Initialize database
aideinit

# Run check
aide --check
```

---

## Phase 14: Maintenance & Monitoring (Ongoing)

### Daily Tasks
```bash
# Check logs
tail -f /var/www/gsmui/storage/logs/laravel.log

# Check failed jobs
php artisan queue:failed

# Check health
curl -f https://gsmui.yourdomain.com/health || echo "Health check failed"

# Backup database
mysqldump -u gsmui_user -p gsmui | gzip > /backups/gsmui-$(date +%Y%m%d).sql.gz
```

### Weekly Tasks
```bash
# Update dependencies
composer update --dry-run

# Check for security updates
php security_audit.php

# Review access logs
grep " 5[0-9][0-9] " /var/log/nginx/access.log | tail -20

# Check disk space
df -h

# Review queue workers
php artisan queue:restart
```

### Monthly Tasks
```bash
# Full backup
tar -czf /backups/gsmui-full-$(date +%Y%m%d).tar.gz \
  /var/www/gsmuri \
  /var/lib/mysql/gsmuri

# Rotate logs
logrotate /etc/logrotate.d/gsmuri

# Update system
apt-get update && apt-get upgrade -y

# Review users and permissions
php artisan users:list
php artisan permissions:list

# Performance review
php artisan telescope:prune --hours=72
```

### Quarterly Tasks
```bash
# Security audit
php artisan security:audit --full

# Penetration testing
# Hire security firm or use automated tools

# Review and update dependencies
composer outdated

# Update documentation
php artisan docs:generate

# Review and optimize database
php artisan optimize

# Disaster recovery test
# Test backup restoration on staging
```

---

## Quick Reference Commands

### Installation
```bash
composer require laravelgpt/gsmui
php artisan gsmui:install
```

### Development
```bash
# Generate component
php artisan gsmui:component {name} --category={cat}

# Run tests
php artisan gsmui:test
php security_audit.php

# Serve application
php artisan serve
```

### Deployment
```bash
# Deploy script
bash deploy.sh

# Rollback
bash rollback.sh
```

### Monitoring
```bash
# Health check
curl https://gsmui.yourdomain.com/health

# Logs
tail -f storage/logs/laravel.log

# Queue
dag --height=3 --watch
```

### Maintenance
```bash
# Optimize
php artisan optimize

# Cache clear
php artisan optimize:clear

# Migrations
php artisan migrate --force

# Backup
mysqldump gsmui | gzip > backup.sql.gz
```

---

## Troubleshooting

### Common Issues

**Issue: White screen after deployment**
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

**Issue: Permission denied**
```bash
chown -R www-data:www-data /var/www/gsmuri
chmod -R 775 /var/www/gsmuri/storage
```

**Issue: Database connection failed**
```bash
php artisan config:clear
php artisan config:cache
# Check .env settings
```

**Issue: Queue not processing**
```bash
php artisan queue:restart
php artisan queue:work --daemon
```

**Issue: 500 Internal Server Error**
```bash
tail -f storage/logs/laravel.log
php artisan route:list
```

---

## Success Metrics

### Performance Targets
- Page load: < 2 seconds
- API response: < 500ms
- Database queries: < 50 per page
- Cache hit rate: > 90%

### Security Targets
- Security audit: 100% pass
- No critical vulnerabilities
- All updates applied within 7 days
- Backups tested monthly

### Availability Targets
- Uptime: 99.9%
- Response time: < 200ms
- Error rate: < 0.1%

### Deployment Targets
- Zero-downtime deployments
- Rollback time: < 5 minutes
- Automated testing: 100% pass

---

## Support & Resources

### Documentation
- Setup Guide: STEP_BY_STEP_GUIDE.md
- Component Library: COMPONENT_SYSTEM_GUIDE.md
- API Documentation: docs/api.md

### Support
- Issues: https://github.com/laravelgpt/gsmui/issues
- Community: https://discord.gg/gsm-ui
- Email: support@gsm-ui.com

### Contributing
- Fork repository
- Create feature branch
- Submit pull request
- Review and merge

---

## Conclusion

By following this guide, you will have a fully functional, secure, and performant GSM-UI Laravel application ready for production.

**Remember:**
- Regular updates and maintenance are crucial
- Monitor your application continuously
- Keep backups and test restoration
- Stay informed about security vulnerabilities
- Optimize performance regularly

**Happy coding! 🎉**

---

**Version:** v2.0.0  
**Last Updated:** April 2026  
**Status:** Production Ready ✅
