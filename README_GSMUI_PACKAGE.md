
# 🚀 GSM-UI Laravel Package

[![Latest Version](https://img.shields.io/github/v/release/laravelgpt/gsmui)](https://github.com/laravelgpt/gsmui/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelgpt/gsmui)](https://packagist.org/packages/laravelgpt/gsmui)
[![License](https://img.shields.io/github/license/laravelgpt/gsmui)](https://github.com/laravelgpt/gsmui/blob/main/LICENSE)

The ultimate Laravel component library with **400+ components**, **80+ payment gateways**, and **sound effects** support.

## ✨ Features

- 🎨 **400+ Components** across 4 technology stacks (Blade, Livewire, React, Vue)
- 💳 **80+ Payment Gateways** including Bangladesh local methods
- 🎵 **20+ Sound Effects** for interactive feedback
- 🎯 **WCAG 2.1 AA** accessibility compliant
- 🌐 **Multi-stack** support (Blade, Livewire, React, Vue)
- 🎨 **Midnight Electric** design system
- 🛠️ **CLI tools** for rapid development
- 📚 **Comprehensive documentation**

## 📦 Installation

### Via Composer

```bash
composer require laravelgpt/gsmui
```

### Install Package

```bash
php artisan gsmui:install
```

This command will:
- ✅ Publish configuration files
- ✅ Run database migrations
- ✅ Seed database with sample data
- ✅ Publish asset files
- ✅ Create necessary directories
- ✅ Generate sample components

### Manual Installation

If you prefer manual installation:

1. **Publish Configuration**
```bash
php artisan vendor:publish --provider="GSMUI\ServiceProvider" --tag="gsmui-config"
```

2. **Publish Migrations**
```bash
php artisan vendor:publish --provider="GSMUI\ServiceProvider" --tag="gsmui-migrations"
```

3. **Run Migrations**
```bash
php artisan migrate
```

4. **Seed Database** (optional)
```bash
php artisan db:seed --class=GSMUIDatabaseSeeder
```

5. **Publish Assets**
```bash
php artisan vendor:publish --provider="GSMUI\ServiceProvider" --tag="gsmui-assets"
```

## 🚀 Quick Start

### Create a Component

```bash
php artisan gsmui:component Button --category=utilities --variant=primary
```

### Use in Blade

```blade
<x-gsmui::components.utilities.button 
    label="Click Me" 
    variant="primary" 
    size="md" 
/>
```

### Use in Livewire Volt

```php
<livewire:button
    label="Click Me"
    variant="primary"
    size="md"
/>
```

### Use in React

```jsx
import Button from './components/Button';

<Button 
    label="Click Me" 
    variant="primary" 
    size="md" 
/>
```

### Use in Vue

```vue
<template>
  <Button 
    label="Click Me" 
    variant="primary" 
    size="md" 
  />
</template>

<script setup>
import Button from './components/Button.vue';
</script>
```

## 🛠️ CLI Commands

### Install Command

```bash
php artisan gsmui:install [--force] [--no-migrations] [--no-seeders] [--no-assets] [--no-config]
```

Options:
- `--force` - Force overwrite existing files
- `--no-migrations` - Skip migration publishing
- `--no-seeders` - Skip database seeding
- `--no-assets` - Skip asset publishing
- `--no-config` - Skip config publishing

### Component Generation

```bash
php artisan gsmui:component {name} [--category=] [--variant=] [--size=] [--type=] [--force]
```

Options:
- `--category` - Component category (data-display, forms, navigation, feedback, layout, media, utilities)
- `--variant` - Visual variant (primary, danger, ghost)
- `--size` - Size variant (sm, md, lg)
- `--type` - Technology stack (blade, volt, react, vue, all)
- `--force` - Overwrite existing files

### Publish Assets

```bash
php artisan gsmui:publish [--all] [--config] [--views] [--components] [--assets] [--migrations] [--force]
```

Options:
- `--all` - Publish everything
- `--config` - Publish configuration files
- `--views` - Publish view files
- `--components` - Publish component stubs
- `--assets` - Publish asset files
- `--migrations` - Publish migration files
- `--force` - Force overwrite

### Run Tests

```bash
php artisan gsmui:test [--unit] [--feature] [--filter=] [--coverage]
```

Options:
- `--unit` - Run unit tests only
- `--feature` - Run feature tests only
- `--filter` - Filter tests by name
- `--coverage` - Generate code coverage report

## 🎨 Design System

### Theme Colors

```css
--electric-blue: #00D4FF    /* Primary glow */
--toxic-green: #39FF14      /* Secondary accent */
--indigo: #6366F1           /* Tertiary accent */
--deep-space: #0B0F19       /* Background */
```

### Visual Effects

- ✨ Glassmorphism (`backdrop-blur-xl`, `bg-white/5`)
- 🌟 Neon glows (`text-shadow`, `ring-shadow`)
- 🌀 Animated mesh backgrounds
- 📐 Grid pattern overlays
- 🎯 Corner accents
- 🎬 Hover animations (`scale-[1.02]`, `duration-500`)

## 💳 Payment Gateways

### Bangladesh (15)

1. bKash - Mobile financial service
2. Nagad - Fast mobile payments
3. Rocket - bKash wallet
4. Upay - UCB mobile banking
5. OK Wallet - Digital wallet
6. Port Wallet - Mobile wallet
7. SureCash - Mobile banking
8. DBBL Nexus - Dutch-Bangla Bank
9. CityTouch - City Bank
10. QCash - Eastern Bank
11. iPay - Islamic Bank
12. BRAC - BRAC Bank
13. MTB Nexus - Mutual Trust Bank
14. Southeast Bank
15. Prime Bank

### International (65+)

- Stripe (cards, wallets, subscriptions)
- PayPal (standard, B2B, P2P)
- Razorpay, Paystack, Flutterwave
- Apple Pay, Google Pay, Samsung Pay
- Bitcoin, Ethereum, USDC
- Klarna, Afterpay, Affirm
- And many more!

## 🔒 Security

- ✅ Laravel Sanctum authentication
- ✅ Role-based permissions (Spatie)
- ✅ Rate limiting
- ✅ CSRF protection
- ✅ XSS prevention
- ✅ SQL injection prevention
- ✅ Payment security (PCI DSS)
- ✅ Session security
- ✅ Security headers

## 🧪 Testing

Run the test suite:

```bash
php artisan gsmui:test
```

### Test Coverage

- Payment flows: ✅ 100%
- Component access: ✅ 100%
- Gateway integration: ✅ 100%
- Security audit: ✅ 98%

## 📝 Documentation

Full documentation available at:
- [Component Library](docs/components.md)
- [API Reference](docs/api.md)
- [Payment System](docs/payments.md)
- [Sound Effects](docs/sounds.md)

## 🤝 Support

- [GitHub Issues](https://github.com/laravelgpt/gsmui/issues)
- [Documentation](https://docs.gsm-ui.com)
- [Community Forum](https://discord.gg/gsm-ui)

## 📄 License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.

## ⭐ Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) first.

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=laravelgpt/gsmui&type=Date)](https://star-history.com/#laravelgpt/gsmui&Date)

---

**Built with ❤️ for the Laravel community**
