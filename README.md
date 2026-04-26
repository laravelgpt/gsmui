# GSM-UI SaaS Marketplace

Premium UI component library and admin template SaaS for data-heavy, GSM/Forensic web applications.

## Tech Stack
- Laravel 13
- Livewire 4 (Volt functional API)
- Tailwind CSS 4
- Alpine.js
- MySQL
- Filament Panel (Admin)
- Laravel Cashier (Stripe)
- Laravel Sanctum (API/CLI Auth)

## Design Language: "Midnight Electric"
- Deep space backgrounds (#0B0F19)
- Glowing neon accents (Electric Blue, Toxic Green)
- Glassmorphism effects
- High-contrast dark mode

## Project Structure
```
app/
  Services/          # Business logic (Payment, Access)
    PaymentService.php
    ComponentAccessService.php
  Console/Commands/  # CLI tools
    GsmAddCommand.php
  Models/
    Component.php
    Template.php
    Purchase.php
  Http/
    Controllers/
    Livewire/
      Components/
      User/
      Admin/
resources/
  views/
    layouts/
      app.blade.php
      dashboard.blade.php
      docs.blade.php
    components/
      ui/
      gsm/
database/
  migrations/
  seeders/
```

## Quick Start
```bash
# Install dependencies
composer install
npm install

# Setup database
php artisan migrate --seed

# Setup Stripe
php artisan cashier:install

# Generate admin user
php artisan make:filament-user
```
