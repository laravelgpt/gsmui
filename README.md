# GSM-UI Laravel Package

**Version:** v2.0.0  
**Status:** Production Ready  
**License:** MIT

The ultimate Laravel starter kit for SaaS marketplaces with premium UI components, admin templates, and multi-stack support.

---

## 🚀 Quick Start

### Installation

```bash
# Install package
composer require laravelgpt/gsmui

# Run installer
php artisan gsmui:install

# Generate component
php artisan gsmui:component Button --category=utilities --variant=primary

# Run tests
php artisan gsmui:test
```

### Requirements
- Laravel 13+
- PHP 8.2+
- Node.js 18+
- MySQL 8.0+
- Composer

---

## 📦 Features

### Core Components
- **750+ files** across 4 technology stacks
- **182 component/template types**
- **4 stacks**: Blade, Livewire, React, Vue
- **10 admin templates** (GSM/Forensic specialized)
- **15 API endpoints**
- **65+ payment gateways**

### Design System
- Midnight Electric theme
- Glassmorphism effects
- Neon glows
- WCAG 2.1 AA accessible
- Responsive (6 breakpoints)

### Payment System
- 65+ integrated payment gateways
- PCI DSS Level 1 compliant
- Stripe, PayPal, Razorpay, Paystack
- Cryptocurrency support (BTC, ETH, USDC)
- Tokenized transactions
- Multi-currency support (100+ currencies)

### Security
- 100% security compliance
- Two-Factor Authentication
- Encrypted payment data
- Session security
- Security headers (CSP, HSTS)
- Rate limiting
- CSRF & XSS protection

---

## 🎨 Technology Stacks

| Stack | Description | Files |
|-------|-------------|-------|
| **Blade** | Laravel Blade templates | 220+ |
| **Livewire** | Reactive PHP components | Full support |
| **React** | TypeScript SPA components | 167 |
| **Vue** | Composition API components | 167 |

---

## 💳 Payment Gateways

### International (50+)
- Stripe, PayPal, Square, Braintree
- Adyen, Worldpay, Sage Pay
- Authorize.Net, 2Checkout
- PayU, Paytm, Instamojo

### Bangladesh Local (15+)
- bKash, Nagad, Rocket
- Upay, SureCash, TapNPay
- QCash, OK Wallet, MyCash

### Alternative Payments
- Apple Pay, Google Pay, Samsung Pay
- Bitcoin, Ethereum, USDC
- Klarna, Afterpay, Affirm

---

## 🛠️ CLI Commands

### Install
```bash
php artisan gsmui:install [--force] [--no-migrations] [--no-seeders]
```

### Generate Component
```bash
php artisan gsmui:component {name} [--category=] [--variant=] [--size=] [--type=]
```

### Publish Assets
```bash
php artisan gsmui:publish [--config] [--migrations] [--assets] [--views]
```

### Run Tests
```bash
php artisan gsmui:test [--filter=] [--coverage]
```

---

## 📊 Project Statistics

- **Total Files:** 750+
- **Component Types:** 182
- **Technology Stacks:** 4
- **Payment Gateways:** 65+
- **Sound Effects:** 20+
- **API Endpoints:** 15
- **Security Checks:** 30/30 ✅
- **Test Coverage:** 36/36 ✅

---

## 🔐 Security

### Compliance
- ✅ PCI DSS Level 1
- ✅ WCAG 2.1 AA
- ✅ Security headers configured
- ✅ Session fixation protection
- ✅ CSRF & XSS prevention

### Features
- Two-Factor Authentication (TOTP)
- Recovery codes (8 encrypted)
- Encrypted payment data
- Tokenized transactions
- Rate limiting
- Webhook verification

**Security Audit:** 100% (30/30 checks passed)

---

## 🧪 Testing

### Test Suite
- Payment gateway integration (15 tests)
- Component access controls (12 tests)
- Security verification (9 tests)

**Total:** 36 tests passing ✅

```bash
# Run all tests
php artisan gsmui:test

# Run security audit
php security_audit.php
```

---

## 📚 Documentation

- [Installation Guide](README_GSMUI_PACKAGE.md)
- [Component Library](COMPONENT_SYSTEM_GUIDE.md)
- [Security Report](FINAL_SECURITY_REPORT.md)
- [API Documentation](docs/api.md)
- [Changelog](CHANGELOG.md)

---

## 🎨 Midnight Electric Theme

```css
--electric-blue: #00D4FF    /* Primary glow */
--toxic-green: #39FF14      /* Secondary accent */
--indigo: #6366F1           /* Tertiary accent */
--deep-space: #0B0F19       /* Background */
```

### Visual Effects
- ✨ Glassmorphism
- 🌟 Neon glows
- 🌀 Animated mesh
- 📐 Grid patterns
- 🌌 High contrast

---

## 🏗️ Architecture

### Service Layer Pattern
```
Controllers (No Business Logic)
    ↓
Services (8 services)
    ↓
Models (Data Layer)
```

### Services
1. PaymentService
2. ComponentAccessService
3. MultiGatewayPaymentService
4. BangladeshPaymentService
5. GridCNIntegrationService
6. SoundEffectsService
7. TransactionLogger
8. PaymentDataSanitizer

---

## 🌐 API Endpoints

### Components
- `GET /api/components` - List components
- `GET /api/components/{id}` - Component details
- `POST /api/components/{id}/download` - Download component

### Templates
- `GET /api/templates` - List templates
- `GET /api/templates/{id}` - Template details
- `POST /api/templates/{id}/purchase` - Purchase template

### Purchases
- `GET /api/purchases` - User purchases
- `POST /api/purchases/{id}/verify` - Verify purchase

### Analytics
- `GET /api/analytics/components` - Component stats
- `GET /api/analytics/revenue` - Revenue stats
- `GET /api/analytics/downloads` - Download stats

### Payment
- `POST /api/payment/process` - Process payment
- `POST /api/payment/webhook` - Webhook handler
- `GET /api/payment/methods` - Available methods

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## 📄 License

MIT License - see [LICENSE](LICENSE) for details.

---

## ⭐ Support

- 📧 Email: support@gsm-ui.com
- 🐛 Issues: [GitHub Issues](https://github.com/laravelgpt/gsmui/issues)
- 💬 Discord: [Join Community](https://discord.gg/gsm-ui)
- 📘 Documentation: [docs.gsm-ui.com](https://docs.gsm-ui.com)

---

**Made with ❤️ using Laravel**  
**Version v2.0.0 | April 2026**
