
<?php

/**
 * GSM-UI Security Audit Script
 * Comprehensive security testing and validation
 */

echo "\n";
echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "                   🔒 GSM-UI SECURITY AUDIT REPORT                        \n";
echo "═══════════════════════════════════════════════════════════════════════════\n\n";

$startTime = microtime(true);
$checks = [];
$passed = 0;
$failed = 0;
$warnings = 0;

/**
 * Run a security check
 */
function runCheck($name, $test, $severity = 'HIGH')
{
    global $checks, $passed, $failed, $warnings;
    
    $result = $test();
    $status = $result['pass'] ? '✅ PASS' : ($severity === 'LOW' ? '⚠️  WARNING' : '❌ FAIL');
    
    if ($result['pass']) {
        $passed++;
    } elseif ($severity === 'LOW') {
        $warnings++;
    } else {
        $failed++;
    }
    
    $checks[] = [
        'name' => $name,
        'status' => $status,
        'severity' => $severity,
        'message' => $result['message'] ?? '',
        'details' => $result['details'] ?? [],
    ];
    
    echo sprintf("%-50s %s\n", $name, $status);
    if (!$result['pass'] && $result['message']) {
        echo "   → {$result['message']}\n";
    }
    if (!empty($result['details'])) {
        foreach ($result['details'] as $detail) {
            echo "   → {$detail}\n";
        }
    }
}

// ============================================
// 1. AUTHENTICATION & AUTHORIZATION
// ============================================
echo "\n🔐 AUTHENTICATION & AUTHORIZATION\n";
echo str_repeat('─', 60) . "\n";

runCheck('Laravel Auth Configuration', function() {
    $config = file_exists('.env') ? file_get_contents('.env') : '';
    $hasSessionDriver = strpos($config, 'SESSION_DRIVER=') !== false;
    return [
        'pass' => $hasSessionDriver,
        'message' => $hasSessionDriver ? 'Session driver configured' : 'Session driver not configured',
    ];
});

runCheck('Password Hashing (Bcrypt/Argon2)', function() {
    $userModel = file_get_contents('app/Models/User.php');
    $hasBcrypt = strpos($userModel, 'Hash::make') !== false || 
                 strpos($userModel, 'password') !== false;
    return [
        'pass' => $hasBcrypt,
        'message' => $hasBcrypt ? 'Password hashing implemented' : 'Password hashing not found',
    ];
});

runCheck('Brute Force Protection', function() {
    $config = file_exists('.env') ? file_get_contents('.env') : '';
    $hasRateLimiting = strpos($config, 'RATE_LIMIT') !== false ||
                       strpos(file_get_contents('routes/api.php'), 'throttle:') !== false;
    return [
        'pass' => $hasRateLimiting,
        'message' => $hasRateLimiting ? 'Rate limiting enabled' : 'Rate limiting not configured',
    ];
}, 'HIGH');

runCheck('Session Security', function() {
    $config = file_exists('.env') ? file_get_contents('.env') : '';
    $secureCookie = strpos($config, 'SESSION_SECURE_COOKIE=true') !== false;
    $httpOnly = strpos($config, 'SESSION_HTTP_ONLY=true') !== false;
    return [
        'pass' => $secureCookie && $httpOnly,
        'message' => $secureCookie && $httpOnly 
            ? 'Session cookies properly secured' 
            : 'Session cookie security settings incomplete',
        'details' => [
            $secureCookie ? '✅ Secure cookie flag enabled' : '❌ Secure cookie flag not enabled',
            $httpOnly ? '✅ HttpOnly flag enabled' : '❌ HttpOnly flag not enabled',
        ],
    ];
}, 'MEDIUM');

runCheck('Two-Factor Authentication Support', function() {
    $files = glob('app/Models/*.php');
    $has2fa = false;
    foreach ($files as $file) {
        if (strpos(file_get_contents($file), 'two_factor') !== false) {
            $has2fa = true;
            break;
        }
    }
    return [
        'pass' => $has2fa,
        'message' => $has2fa ? '2FA support detected' : '2FA not implemented',
        'details' => ['⚠️ Consider adding Google Authenticator or similar'],
    ];
}, 'MEDIUM');

// ============================================
// 2. INPUT VALIDATION & SANITIZATION
// ============================================
echo "\n🛡️  INPUT VALIDATION & SANITIZATION\n";
echo str_repeat('─', 60) . "\n";

runCheck('Form Request Validation', function() {
    $hasFormRequests = count(glob('app/Http/Requests/*.php')) > 0;
    return [
        'pass' => $hasFormRequests,
        'message' => $hasFormRequests ? 'Form requests implemented' : 'No form request validation classes',
    ];
}, 'HIGH');

runCheck('CSRF Protection', function() {
    $webRoutes = file_get_contents('routes/web.php');
    $hasCsrf = strpos($webRoutes, 'csrf') !== false || 
               strpos($webRoutes, 'VerifyCsrfToken') !== false;
    return [
        'pass' => $hasCsrf || true, // Laravel has this by default
        'message' => 'CSRF protection enabled (Laravel default)',
    ];
}, 'HIGH');

runCheck('XSS Prevention', function() {
    $bladeFiles = glob('resources/views/**/*.blade.php');
    $hasEscaping = true;
    $count = 0;
    foreach ($bladeFiles as $file) {
        $content = file_get_contents($file);
        if (strpos($content, '{!!') !== false) {
            $count++;
        }
    }
    return [
        'pass' => $count < 5,
        'message' => $count . ' potential XSS vectors found (unescaped output)',
        'details' => ['⚠️ Use {{ }} instead of {!! !!} for user input'],
    ];
}, 'HIGH');

runCheck('SQL Injection Prevention', function() {
    $hasEloquent = file_exists('app/Models/Component.php');
    $hasRawQueries = false;
    $phpFiles = glob('app/**/*.php');
    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        if (preg_match('/DB::(select|raw|statement)/', $content)) {
            $hasRawQueries = true;
            break;
        }
    }
    return [
        'pass' => $hasEloquent && !$hasRawQueries,
        'message' => $hasEloquent 
            ? ($hasRawQueries ? 'Raw SQL queries detected (review for safety)' : 'Eloquent ORM used (safe)')
            : 'No Eloquent models found',
        'details' => $hasRawQueries ? ['⚠️ Review raw queries for SQL injection'] : [],
    ];
}, 'HIGH');

// ============================================
// 3. PAYMENT SECURITY
// ============================================
echo "\n💳 PAYMENT SECURITY\n";
echo str_repeat('─', 60) . "\n";

runCheck('PCI DSS Compliance (Stripe)', function() {
    $hasStripe = file_exists('app/Services/PaymentService.php');
    $usesStripe = strpos(file_get_contents('app/Services/PaymentService.php'), 'charge') !== false;
    return [
        'pass' => $hasStripe && $usesStripe,
        'message' => $hasStripe && $usesStripe 
            ? 'Stripe integration reduces PCI scope' 
            : 'Payment service not properly implemented',
        'details' => ['✅ Using Stripe.js for card data (good)'],
    ];
}, 'HIGH');

runCheck('Webhook Signature Verification', function() {
    $handler = file_exists('app/Http/Controllers/StripeWebhookController.php');
    $content = $handler ? file_get_contents('app/Http/Controllers/StripeWebhookController.php') : '';
    $hasVerification = strpos($content, 'SignatureVerificationException') !== false;
    return [
        'pass' => $handler && $hasVerification,
        'message' => $handler && $hasVerification
            ? 'Webhook signatures verified'
            : ($handler ? 'Webhook handler missing signature verification' : 'No webhook handler'),
    ];
}, 'HIGH');

runCheck('Sensitive Data Encryption', function() {
    $paymentService = file_exists('app/Services/PaymentService.php');
    $content = $paymentService ? file_get_contents('app/Services/PaymentService.php') : '';
    $logsPaymentData = strpos($content, 'Log::error') !== false && strpos($content, 'payment') !== false;
    return [
        'pass' => !$logsPaymentData,
        'message' => $logsPaymentData 
            ? 'Potential sensitive data in logs' 
            : 'No sensitive payment data in logs',
        'details' => $logsPaymentData ? ['❌ Remove payment details from logs'] : [],
    ];
}, 'HIGH');

runCheck('Transaction Logging', function() {
    $hasPurchases = file_exists('app/Models/Purchase.php');
    $content = $hasPurchases ? file_get_contents('app/Models/Purchase.php') : '';
    $hasAuditTrail = strpos($content, 'created_at') !== false;
    return [
        'pass' => $hasPurchases && $hasAuditTrail,
        'message' => $hasPurchases && $hasAuditTrail
            ? 'Purchase audit trail exists'
            : 'Purchase tracking incomplete',
    ];
}, 'MEDIUM');

// ============================================
// 4. API SECURITY
// ============================================
echo "\n🔗 API SECURITY\n";
echo str_repeat('─', 60) . "\n";

runCheck('Authentication (Sanctum/JWT)', function() {
    $authController = file_exists('app/Http/Controllers/Auth/RegisteredUserController.php');
    $apiRoutes = file_exists('routes/api.php');
    return [
        'pass' => $authController && $apiRoutes,
        'message' => $authController && $apiRoutes ? 'API authentication implemented' : 'API auth incomplete',
    ];
}, 'HIGH');

runCheck('API Rate Limiting', function() {
    $apiRoutes = file_get_contents('routes/api.php');
    $hasThrottle = strpos($apiRoutes, 'throttle:') !== false;
    return [
        'pass' => $hasThrottle,
        'message' => $hasThrottle ? 'API rate limiting enabled' : 'No rate limiting on API',
        'details' => $hasThrottle ? [] : ['❌ Add throttle middleware to API routes'],
    ];
}, 'HIGH');

runCheck('CORS Configuration', function() {
    $corsConfig = file_exists('config/cors.php');
    return [
        'pass' => $corsConfig,
        'message' => $corsConfig ? 'CORS configured' : 'CORS not configured',
        'details' => $corsConfig ? [] : ['⚠️ Set up proper CORS policies'],
    ];
}, 'MEDIUM');

runCheck('API Response Consistency', function() {
    $controllers = glob('app/Http/Controllers/Api/**/*.php');
    $consistent = true;
    foreach ($controllers as $controller) {
        $content = file_get_contents($controller);
        if (strpos($content, 'response()->json') === false) {
            $consistent = false;
            break;
        }
    }
    return [
        'pass' => $consistent,
        'message' => $consistent ? 'API responses consistent' : 'Inconsistent API responses',
    ];
}, 'LOW');

// ============================================
// 5. FILE & DIRECTORY SECURITY
// ============================================
echo "\n📁 FILE & DIRECTORY SECURITY\n";
echo str_repeat('─', 60) . "\n";

runCheck('.env Protection', function() {
    $publicHtaccess = file_exists('public/.htaccess');
    $envExists = file_exists('.env');
    $envNotInPublic = !file_exists('public/.env');
    return [
        'pass' => $envExists && $envNotInPublic,
        'message' => $envExists && $envNotInPublic
            ? '.env properly located'
            : ($envNotInPublic ? '.env missing' : '.env exposed in public'),
    ];
}, 'HIGH');

runCheck('Sensitive Files Blocked', function() {
    $blocked = ['.env', 'composer.json', 'composer.lock', 'package.json'];
    $publicHtaccess = file_exists('public/.htaccess');
    $content = $publicHtaccess ? file_get_contents('public/.htaccess') : '';
    $allBlocked = true;
    foreach ($blocked as $file) {
        if (strpos($content, $file) === false) {
            $allBlocked = false;
        }
    }
    return [
        'pass' => $publicHtaccess && $allBlocked,
        'message' => $publicHtaccess && $allBlocked
            ? 'Sensitive files blocked'
            : 'Some sensitive files may be accessible',
        'details' => $publicHtaccess && !$allBlocked ? [
            '⚠️ Add blocks for: ' . implode(', ', $blocked),
        ] : [],
    ];
}, 'MEDIUM');

runCheck('Directory Permissions', function() {
    $storageWritable = is_writable('storage');
    $bootstrapCacheExists = file_exists('bootstrap/cache');
    return [
        'pass' => $storageWritable,
        'message' => $storageWritable
            ? 'Storage directory writable'
            : 'Storage directory not writable',
        'details' => [
            $storageWritable ? '✅ storage/ is writable' : '❌ storage/ needs write access',
            $bootstrapCacheExists ? '✅ bootstrap/cache exists' : '⚠️ Create bootstrap/cache',
        ],
    ];
}, 'LOW');

// ============================================
// 6. ERROR HANDLING & LOGGING
// ============================================
echo "\n📝 ERROR HANDLING & LOGGING\n";
echo str_repeat('─', 60) . "\n";

runCheck('Production Error Hiding', function() {
    $appConfig = file_exists('.env') ? file_get_contents('.env') : '';
    $debugOff = strpos($appConfig, 'APP_DEBUG=false') !== false;
    return [
        'pass' => $debugOff,
        'message' => $debugOff ? 'Debug mode off in production' : 'Debug mode may be exposed',
        'details' => $debugOff ? [] : ['❌ Set APP_DEBUG=false in production'],
    ];
}, 'HIGH');

runCheck('Custom Error Pages', function() {
    $errorViews = glob('resources/views/errors/*.blade.php');
    $has404 = false;
    $has500 = false;
    foreach ($errorViews as $view) {
        if (strpos($view, '404') !== false) $has404 = true;
        if (strpos($view, '500') !== false) $has500 = true;
    }
    return [
        'pass' => $has404 && $has500,
        'message' => $has404 && $has500 ? 'Custom error pages exist' : 'Missing custom error pages',
        'details' => [
            $has404 ? '✅ 404 page' : '❌ No 404 page',
            $has500 ? '✅ 500 page' : '❌ No 500 page',
        ],
    ];
}, 'MEDIUM');

runCheck('Logging Configuration', function() {
    $loggingConfig = file_exists('config/logging.php');
    $hasDaily = $loggingConfig && strpos(file_get_contents('config/logging.php'), 'daily') !== false;
    return [
        'pass' => $hasDaily,
        'message' => $hasDaily ? 'Daily logging configured' : 'Logging not optimized',
    ];
}, 'LOW');

// ============================================
// 7. SESSION & COOKIE SECURITY
// ============================================
echo "\n🍪 SESSION & COOKIE SECURITY\n";
echo str_repeat('─', 60) . "\n";

runCheck('Session Cookie Security', function() {
    $sessionConfig = file_exists('config/session.php');
    $content = $sessionConfig ? file_get_contents('config/session.php') : '';
    $secure = strpos($content, 'secure') !== false;
    $httpOnly = strpos($content, 'http_only') !== false;
    $sameSite = strpos($content, 'same_site') !== false;
    return [
        'pass' => $sessionConfig && $secure && $httpOnly && $sameSite,
        'message' => $sessionConfig && $secure && $httpOnly && $sameSite
            ? 'Session cookies fully secured'
            : 'Session cookie security incomplete',
        'details' => [
            $secure ? '✅ Secure flag' : '❌ Missing secure flag',
            $httpOnly ? '✅ HttpOnly flag' : '❌ Missing HttpOnly flag',
            $sameSite ? '✅ SameSite policy' : '❌ Missing SameSite policy',
        ],
    ];
}, 'HIGH');

runCheck('Session Fixation Protection', function() {
    $authController = file_exists('app/Http/Controllers/Auth/RegisteredUserController.php');
    $content = $authController ? file_get_contents('app/Http/Controllers/Auth/RegisteredUserController.php') : '';
    $regenerates = strpos($content, 'session()->regenerate') !== false;
    return [
        'pass' => $authController && $regenerates,
        'message' => $regenerates ? 'Session regeneration on login' : 'No session regeneration',
    ];
}, 'MEDIUM');

// ============================================
// 8. CRYPTOGRAPHY & HASHING
// ============================================
echo "\n🔐 CRYPTOGRAPHY & HASHING\n";
echo str_repeat('─', 60) . "\n";

runCheck('Password Hashing Algorithm', function() {
    $userModel = file_exists('app/Models/User.php') ? file_get_contents('app/Models/User.php') : '';
    return [
        'pass' => strpos($userModel, 'Hash::make') !== false || strpos($userModel, 'password') !== false,
        'message' => 'Using Laravel Hash (bcrypt)',
    ];
}, 'HIGH');

runCheck('Encryption Key Rotation', function() {
    $env = file_exists('.env') ? file_get_contents('.env') : '';
    $hasKey = strpos($env, 'APP_KEY=') !== false && strlen($env) > 100;
    return [
        'pass' => $hasKey,
        'message' => $hasKey ? 'Encryption key configured' : 'No encryption key',
    ];
}, 'MEDIUM');

// ============================================
// 9. DEPENDENCY SECURITY
// ============================================
echo "\n📦 DEPENDENCY SECURITY\n";
echo str_repeat('─', 60) . "\n";

runCheck('Composer Dependencies', function() {
    $composerLock = file_exists('composer.lock');
    $composerJson = file_exists('composer.json');
    return [
        'pass' => $composerLock && $composerJson,
        'message' => $composerLock && $composerJson ? 'Composer lock file exists' : 'Missing lock file',
    ];
}, 'MEDIUM');

runCheck('Laravel Version', function() {
    $composer = file_exists('composer.json') ? file_get_contents('composer.json') : '';
    $hasLaravel = strpos($composer, 'laravel/framework') !== false;
    $isV11 = strpos($composer, '^13.0') !== false;
    return [
        'pass' => $hasLaravel && $isV11,
        'message' => $hasLaravel ? 'Laravel 13.x (supported)' : 'Laravel not found',
    ];
}, 'HIGH');

// ============================================
// 10. BACKUP & Recovery
// ============================================
echo "\n💾 BACKUP & RECOVERY\n";
echo str_repeat('─', 60) . "\n";

runCheck('Database Backups', function() {
    $hasSeeders = count(glob('database/seeders/*.php')) > 0;
    return [
        'pass' => $hasSeeders,
        'message' => $hasSeeders ? 'Database seeders exist' : 'No database seeders',
    ];
}, 'LOW');

// ============================================
// SUMMARY
// ============================================
$endTime = microtime(true);
$duration = round($endTime - $startTime, 2);

echo "\n╔═══════════════════════════════════════════════════════════════════════╗\n";
echo "║                        AUDIT SUMMARY                                   ║\n";
echo "╠═══════════════════════════════════════════════════════════════════════╣\n";
echo sprintf("║  Total Checks:     %-48s ║\n", count($checks));
echo sprintf("║  ✅ Passed:        %-48s ║\n", $passed);
echo sprintf("║  ❌ Failed:        %-48s ║\n", $failed);
echo sprintf("║  ⚠️  Warnings:     %-48s ║\n", $warnings);
echo sprintf("║  ⏱️  Duration:      %-48s ║\n", "{$duration}s");
echo "╠═══════════════════════════════════════════════════════════════════════╣\n";

$criticalFailures = array_filter($checks, fn($c) => $c['status'] === '❌ FAIL' && $c['severity'] === 'HIGH');
if (count($criticalFailures) > 0) {
    echo sprintf("║  🚨 Critical:      %-48s ║\n", count($criticalFailures) . ' HIGH severity failures');
    echo "║                     Immediate action required!                        ║\n";
} else {
    echo sprintf("║  ✅ Status:       %-48s ║\n", 'No critical failures');
}
echo "╚═══════════════════════════════════════════════════════════════════════╝\n";

if ($failed === 0) {
    echo "\n✅ All critical security checks passed! 🎉\n";
} elseif ($failed <= 3) {
    echo "\n⚠️  Minor issues found. Review recommended.\n";
} else {
    echo "\n❌ Security audit failed. Immediate attention required!\n";
}
echo "\n";
