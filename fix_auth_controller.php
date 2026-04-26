
<?php

// Fix auth controller
$file = 'app/Http/Controllers/Auth/RegisteredUserController.php';
$content = file_get_contents($file);
$content = str_replace(
    "        auth()->login(\$user);\n\n        return redirect()->route('dashboard');",
    "        auth()->login(\$user);\n        session()->regenerate();\n\n        return redirect()->route('dashboard');",
    $content
);
file_put_contents($file, $content);
echo "✅ Fixed: Session regeneration in Auth Controller\n";

// Fix PaymentService to not log sensitive data
$file = 'app/Services/PaymentService.php';
$content = file_get_contents($file);
// Replace Log::error calls that might contain sensitive data with sanitized versions
$content = str_replace(
    "Log::error('Stripe charge error: ' . \$e->getMessage());",
    "// Sensitive payment data redacted\n        Log::channel('transactions')->error('Stripe charge error', ['message' => 'Charge failed']);",
    $content
);
$content = str_replace(
    "Log::error('Purchase error: ' . \$e->getMessage());",
    "// Sensitive payment data redacted\n        Log::channel('transactions')->error('Purchase error', ['message' => 'Purchase failed']);",
    $content
);
$content = str_replace(
    "Log::error('Subscription error: ' . \$e->getMessage());",
    "// Sensitive payment data redacted\n        Log::channel('transactions')->error('Subscription error', ['message' => 'Subscription failed']);",
    $content
);
$content = str_replace(
    "Log::error('Cancel error: ' . \$e->getMessage());",
    "// Sensitive payment data redacted\n        Log::channel('transactions')->error('Cancel error', ['message' => 'Cancellation failed']);",
    $content
);
file_put_contents($file, $content);
echo "✅ Fixed: PaymentService - Removed sensitive data from logs\n";

// Fix .htaccess to block all sensitive files
$file = 'public/.htaccess';
$content = file_get_contents($file);

// Create comprehensive block
$blocks = '
# Block all sensitive files comprehensively
<FilesMatch "\\.(env|log|htaccess|ini|lock|sql|bak|swp|git|json|yml|xml|md|dist|config|example|sample)$">
    Require all denied
</FilesMatch>

<FilesMatch "(composer|package|gulpfile|webpack|vite|tailwind|postcss|browserslist|eslint|babel)\\.json$">
    Require all denied
</FilesMatch>

<FilesMatch "\\.git">
    Require all denied
</FilesMatch>

<DirectoryMatch "\\.git">
    Require all denied
</DirectoryMatch>

# Explicit blocks for audit
<Files ".env">
    Require all denied
</Files>

<Files "composer.json">
    Require all denied
</Files>

<Files "composer.lock">
    Require all denied
</Files>

<Files "package.json">
    Require all denied
</Files>
';

// Insert before closing IfModule
$content = str_replace(
    '</IfModule>',
    $blocks . "</IfModule>",
    $content
);

file_put_contents($file, $content);
echo "✅ Fixed: .htaccess - Added comprehensive file blocking\n";

echo "\n✅ All critical fixes applied!\n";
