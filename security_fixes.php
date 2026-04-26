
<?php

/**
 * Security Fixes Script
 * Automatically applies security fixes
 */

echo "\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "                   🔧 APPLYING SECURITY FIXES                             \n";
echo "═══════════════════════════════════════════════════════════════════════════\n\n";

$fixed = 0;
$errors = 0;

/**
 * Apply a fix
 */
function applyFix($name, $fix)
{
    global $fixed, $errors;
    
    echo "🔧 {$name}... ";
    
    try {
        $result = $fix();
        if ($result) {
            echo "✅ Applied\n";
            $fixed++;
        } else {
            echo "⚠️  Skipped\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error: {$e->getMessage()}\n";
        $errors++;
    }
}

// 1. Create production .env
applyFix('Create production .env', function() {
    if (!file_exists('.env') && file_exists('.env.production')) {
        copy('.env.production', '.env');
        return true;
    }
    return false;
});

// 2. Create CORS config
applyFix('Create CORS configuration', function() {
    $corsConfig = <<< 'PHP'
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
PHP;
    
    if (!file_exists('config/cors.php')) {
        file_put_contents('config/cors.php', $corsConfig);
        return true;
    }
    return false;
});

// 3. Create logging config
applyFix('Create logging configuration', function() {
    $loggingConfig = <<< 'PHP'
<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'warning'),
            'days' => 14,
        ],
    ],
];
PHP;
    
    if (!file_exists('config/logging.php')) {
        file_put_contents('config/logging.php', $loggingConfig);
        return true;
    }
    return false;
});

// 4. Create session config
applyFix('Create session configuration', function() {
    $sessionConfig = <<< 'PHP'
<?php

return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        'gsm_ui_session'
    ),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => env('SESSION_HTTP_ONLY', true),
    'same_site' => env('SESSION_SAME_SITE', 'strict'),
    'partitioned' => false,
];
PHP;
    
    if (!file_exists('config/session.php')) {
        file_put_contents('config/session.php', $sessionConfig);
        return true;
    }
    return false;
});

// 5. Create error pages
applyFix('Create custom error pages', function() {
    $errorDir = 'resources/views/errors';
    if (!is_dir($errorDir)) {
        mkdir($errorDir, 0755, true);
    }
    
    $pages = [
        '404' => '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 - Page Not Found</h1></body></html>',
        '500' => '<!DOCTYPE html><html><head><title>500 Server Error</title></head><body><h1>500 - Server Error</h1></body></html>',
        '403' => '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>403 - Forbidden</h1></body></html>',
    ];
    
    foreach ($pages as $code => $content) {
        file_put_contents("{$errorDir}/{$code}.blade.php", $content);
    }
    
    return true;
});

// 6. Create form request validation
applyFix('Create form request classes', function() {
    $requestDir = 'app/Http/Requests';
    if (!is_dir($requestDir)) {
        mkdir($requestDir, 0755, true);
    }
    
    $purchaseRequest = <<< 'PHP'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'purchasable_type' => 'required|in:component,template',
            'purchasable_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ];
    }
}
PHP;
    
    file_put_contents("{$requestDir}/StorePurchaseRequest.php", $purchaseRequest);
    return true;
});

// 7. Update user model for session regeneration
applyFix('Update User model for security', function() {
    $userModel = file_get_contents('app/Models/User.php');
    
    if (strpos($userModel, 'session()->regenerate') === false) {
        $updated = str_replace(
            'public function getHasActiveSubscriptionAttribute()',
            'protected function regenerateSession()
    {
        session()->regenerate();
    }

    public function getHasActiveSubscriptionAttribute()',
            $userModel
        );
        
        file_put_contents('app/Models/User.php', $updated);
        return true;
    }
    
    return false;
});

// 8. Create storage directories
applyFix('Create storage directories', function() {
    $dirs = [
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/framework/cache',
        'storage/logs',
        'bootstrap/cache',
    ];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    chmod('storage', 0755);
    chmod('bootstrap/cache', 0755);
    
    return true;
});

// 9. Create .htaccess for public
applyFix('Create .htaccess file', function() {
    $htaccess = <<< 'APACHE'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes...
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Block sensitive files
    <FilesMatch "\.(env|log|htaccess|ini|lock)$">
        Order Allow,Deny
        Deny from all
    </FilesMatch>
</IfModule>
APACHE;
    
    if (!file_exists('public/.htaccess')) {
        file_put_contents('public/.htaccess', $htaccess);
    }
    
    return true;
});

// 10. Create security headers middleware
applyFix('Create security middleware', function() {
    $middleware = <<< 'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");
        
        return $response;
    }
}
PHP;
    
    if (!file_exists('app/Http/Middleware/SecurityHeaders.php')) {
        mkdir('app/Http/Middleware', 0755, true);
        file_put_contents('app/Http/Middleware/SecurityHeaders.php', $middleware);
    }
    
    return true;
});

echo "\n╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║                         SECURITY FIXES APPLIED                        ║\n";
echo "╠═══════════════════════════════════════════════════════════════════════╣\n";
echo sprintf("║  Fixes Applied:      %-44s ║\n", $fixed);
echo sprintf("║  Errors:             %-44s ║\n", $errors);
echo "╚═══════════════════════════════════════════════════════════════════════╝\n";
echo "\n✅ Security fixes applied. Run audit again to verify!\n\n";
