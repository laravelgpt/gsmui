
# GSM-UI Laravel Package - Complete Step-by-Step Guide

## Phase 1: Initial Setup (5 minutes)

### Step 1.1: Install Laravel Project
```bash
composer create-project laravel/laravel gsmui-project
cd gsmui-project
```

### Step 1.2: Install GSM-UI Package
```bash
composer require laravelgpt/gsmui
```

### Step 1.3: Run Package Installer
```bash
php artisan gsmui:install
```
This will:
- Publish configuration files
- Run database migrations
- Create admin user
- Setup payment system
- Configure services

### Step 1.4: Verify Installation
```bash
php artisan gsmui:test
php security_audit.php
```
Expected: All tests passing ✅, Security 100% ✅

---

## Phase 2: Environment Configuration (10 minutes)

### Step 2.1: Configure .env
```bash
# Application
APP_NAME=GSM-UI
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gsmui
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@gsmui.test
MAIL_FROM_NAME="GSM-UI"

# Payment (Stripe)
STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

### Step 2.2: Setup Database
```bash
# Create database (MySQL)
mysql -u root -p -e "CREATE DATABASE gsmui;"

# Run migrations
php artisan migrate --seed
```

### Step 2.3: Configure Payment Gateways
```bash
# Edit config/payment.php
'gateways' => [
    'stripe' => [
        'enabled' => true,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'paypal' => [
        'enabled' => true,
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
    ],
    // Add other gateways as needed
],
```

---

## Phase 3: Security Hardening (15 minutes)

### Step 3.1: Run Security Audit
```bash
php security_audit.php
```

### Step 3.2: Enable Two-Factor Auth
```php
// In config/gsmui.php
'two_factor_auth' => [
    'enabled' => true,
    'issuer' => 'GSM-UI',
],
```

### Step 3.3: Configure Rate Limiting
```php
// In app/Http/Kernel.php
'api' => [
    'throttle:60,1', // 60 requests per minute
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### Step 3.4: Setup Security Headers
```php
// config/security.php
return [
    'headers' => [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Content-Security-Policy' => "default-src 'self'",
    ],
];
```

### Step 3.5: Verify Security
```bash
php security_audit.php
# Expected: 30/30 checks passed ✅
```

---

## Phase 4: Component Generation (20 minutes)

### Step 4.1: Generate Your First Component
```bash
# Basic component
php artisan gsmui:component PrimaryButton --category=ui --variant=primary

# Multi-stack component
php artisan gsmui:component DataCard --category=data --stacks=all

# Form component
php artisan gsmui:component TextInput --category=forms --variant=default
```

### Step 4.2: Component Structure
Generated files:
```
app/Components/Blade/Class/PrimaryButton.php
app/Components/Livewire/Volt/PrimaryButton.php
app/Components/React/components/PrimaryButton.jsx
app/Components/Vue/components/PrimaryButton.vue
resources/views/components/docs/primary-button.md
```

### Step 4.3: Use Generated Components

**Blade:**
```blade
<x-gsmui::components.ui.primary-button 
    label="Click Me" 
    variant="primary" 
    size="md"
/>
```

**Livewire:**
```php
<livewire:components.ui.primary-button
    label="Click Me"
    variant="primary"
    size="md"
/>
```

**React:**
```jsx
import { PrimaryButton } from './components/ui/PrimaryButton';

<PrimaryButton 
    label="Click Me" 
    variant="primary" 
    size="md"
/>
```

**Vue:**
```vue
<script setup>
import PrimaryButton from './components/ui/PrimaryButton.vue';
</script>

<template>
  <PrimaryButton 
    label="Click Me" 
    variant="primary" 
    size="md"
  />
</template>
```

### Step 4.4: Customize Components

Edit component classes in:
```
app/Components/Blade/Class/
app/Components/Livewire/Volt/
app/Components/React/components/
app/Components/Vue/components/
```

---

## Phase 5: Payment Integration (30 minutes)

### Step 5.1: Configure Payment Service
```php
// app/Services/PaymentService.php

public function processPayment($user, $component, $amount, $currency, $method)
{
    return $this->multiGateway->process([
        'user_id' => $user->id,
        'component_id' => $component->id,
        'amount' => $amount,
        'currency' => $currency,
        'method' => $method,
    ]);
}
```

### Step 5.2: Setup Payment Routes
```php
// routes/web.php
Route::post('/payment/process', [PaymentController::class, 'process']);
Route::post('/payment/webhook/{gateway}', [PaymentController::class, 'webhook']);
```

### Step 5.3: Create Payment Controller
```php
// app/Http/Controllers/PaymentController.php

public function process(Request $request)
{
    $validated = $request->validate([
        'component_id' => 'required|exists:components,id',
        'amount' => 'required|numeric',
        'currency' => 'required|string',
        'method' => 'required|string',
    ]);

    $result = app(PaymentService::class)->processPayment(
        $request->user(),
        Component::find($validated['component_id']),
        $validated['amount'],
        $validated['currency'],
        $validated['method']
    );

    return response()->json($result);
}
```

### Step 5.4: Test Payment Flow
```bash
# Run payment tests
php artisan test --filter=Payment

# Expected: All passing ✅
```

---

## Phase 6: Admin Panel Setup (20 minutes)

### Step 6.1: Access Admin Panel
```bash
# Visit: http://localhost/admin
# Login with credentials from: php artisan gsmui:install
```

### Step 6.2: Configure Admin Templates
10 pre-built templates available:
- GSM Flasher
- Forensic Log Viewer
- Server Node Monitor
- Network Scanner
- Evidence Management
- Signal Analyzer
- Incident Response
- Data Breach Analyzer
- Mobile Forensics
- SOC Dashboard

### Step 6.3: Customize Admin
```php
// config/gsmui.php
'admin' => [
    'templates' => [
        'default' => 'gsm-flasher',
        'forensic' => 'forensic-viewer',
    ],
    'theme' => 'midnight-electric',
],
```

---

## Phase 7: Testing & Deployment (30 minutes)

### Step 7.1: Run Full Test Suite
```bash
# Security audit
php security_audit.php

# Unit tests
php artisan test

# Integration tests
php artisan gsmui:test

# Expected: All passing ✅
```

### Step 7.2: Performance Optimization
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize
php artisan optimize
```

### Step 7.3: Deploy to Production
```bash
# 1. Push to repository
git remote add origin https://github.com/your-org/gsmui.git
git push -u origin master

# 2. Deploy to server
ssh user@production-server
cd /var/www/gsmui
git pull origin master

# 3. Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production
npm run build

# 4. Run migrations
php artisan migrate --force --seed

# 5. Clear cache
php artisan optimize:clear

# 6. Configure environment
cp .env.example .env
# Edit .env with production values

# 7. Verify deployment
php artisan gsmui:test
php security_audit.php
```

---

## Phase 8: Monitoring & Maintenance (Ongoing)

### Step 8.1: Setup Monitoring
```bash
# Check logs
tail -f storage/logs/laravel.log
tail -f storage/logs/transactions.log

# Monitor health
php artisan gsmui:test
php security_audit.php
```

### Step 8.2: Regular Tasks
- Daily: Check transaction logs
- Weekly: Review security audit
- Monthly: Update dependencies
- Quarterly: Full security review

### Step 8.3: Backup Strategy
```bash
# Database backup
mysqldump -u root -p gsmui > backup-$(date +%Y%m%d).sql

# Files backup
tar -czf gsmui-backup-$(date +%Y%m%d).tar.gz \
    storage/ \
    bootstrap/cache/ \
    config/
```

---

## Quick Reference

### Essential Commands
```bash
# Install
composer require laravelgpt/gsmui
php artisan gsmui:install

# Generate component
php artisan gsmui:component {name} --category={cat} --variant={var}

# Run tests
php artisan gsmui:test
php security_audit.php

# Publish assets
php artisan gsmui:publish

# Check status
php artisan gsmui:status
```

### File Locations
```
Components:    app/Components/
Services:      app/Services/
Controllers:   app/Http/Controllers/
Config:        config/gsmui.php
Tests:         tests/
Logs:          storage/logs/
Migrations:    database/migrations/
```

### Support
- Documentation: https://docs.gsm-ui.com
- GitHub: https://github.com/laravelgpt/gsmui
- Issues: https://github.com/laravelgpt/gsmui/issues

---

## Troubleshooting

### Issue: Component not found
```bash
php artisan view:clear
php artisan cache:clear
composer dump-autoload
```

### Issue: Payment gateway error
```bash
# Check credentials in .env
# Verify webhook URL
# Check gateway logs
```

### Issue: Security audit fails
```bash
# Run fixes
php security_fixes.php

# Re-audit
php security_audit.php
```

---

**Version:** v2.0.0  
**Last Updated:** April 2026  
**Status:** Production Ready ✅
