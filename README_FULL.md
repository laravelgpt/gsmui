# GSM-UI SaaS Marketplace

A premium UI component library and admin template SaaS platform built with Laravel 13, Livewire 4, Tailwind CSS 4, and Alpine.js. Designed specifically for data-heavy, GSM/Forensic web applications with a "Midnight Electric" dark theme featuring glassmorphism effects and neon accents.

## 🌟 Features

- **50+ UI Components** - Data grids, filters, actions, status indicators, and more
- **10+ Admin Templates** - Specialized dashboards for GSM/Forensic workflows
- **CLI Tool** - Download components directly to your project
- **Service Layer Architecture** - Clean separation of business logic
- **Role-based Access Control** - Admin and user roles
- **Subscription Management** - Stripe integration with Laravel Cashier
- **Analytics Dashboard** - Real-time metrics and revenue tracking
- **Theme Engine** - Customizable Midnight Electric theme

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+
- Stripe/Paddle account (for payments)

### Installation

```bash
# Clone repository
git clone <repository-url>
cd gsm-ui

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
nano .env  # Configure database and other settings

# Generate app key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Install Filament
php artisan filament:install

# Create admin user
php artisan make:filament-user

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

## 🎨 Design Language: Midnight Electric

- **Primary Background**: `#0B0F19` (Deep Space)
- **Card Background**: `rgba(19, 24, 40, 0.9)` (Glass)
- **Electric Blue**: `#00D4FF` (Primary Accent)
- **Toxic Green**: `#39FF14` (Secondary Accent)
- **Indigo**: `#6366F1` (Tertiary Accent)

Visual elements include:
- Glassmorphism panels with backdrop blur
- Neon glow effects and borders
- Animated mesh background gradient
- Grid pattern overlay
- High-contrast dark mode

## 📦 Components

### Data Display
- Data Grid Pro (Premium)
- Log Viewer Panel (Free)
- Status Indicators (Premium)
- Metric Cards

### Filters
- Multi-Select Filter (Premium)
- Date Range Picker (Free)
- Search Inputs
- Tag Filters

### Actions
- Action Dropdown (Free)
- Data Export Button (Premium)
- Bulk Action Toolbar

### Feedback
- Alert Banners (Free)
- Status Badges
- Toast Notifications
- Progress Indicators

## 🛠 CLI Tool

```bash
# Download a component
php artisan gsm:add data-grid-pro --token=your-token

# Set token in .env
GSM_TOKEN=your-token
```

The CLI:
- Authenticates via Personal Access Token
- Pings the SaaS API for validation
- Returns 403 for unauthorized premium components
- Streams code to `resources/views/components/gsm/`

## 🎯 Admin Templates

1. **GSM Flasher Dashboard** ($199.99) - Terminal + device monitor
2. **Forensic Log Viewer** ($149.99) - Full-width datagrid
3. **Server Node Monitor** ($179.99) - Circular progress orbs
4. **Network Scanner** ($169.99) - Host discovery & port viz
5. **Evidence Management** ($189.99) - Case tracking system
6. **Signal Analyzer** ($159.99) - Spectrum visualization
7. **Incident Response** ($209.99) - Ticketing & timeline
8. **Data Breach Analyzer** ($199.99) - Impact mapping
9. **Mobile Forensics** ($229.99) - Extraction workflow
10. **SOC Dashboard** ($299.99) - Threat intelligence hub

## 💳 Monetization

### Subscription Plans
- **Free Tier**: Access to free components
- **Pro Plan**: $29.99/month - All premium components & templates

### One-Time Purchases
- Individual component licenses
- Lifetime template access
- No recurring fees

### Payment Processing
- Stripe integration via Laravel Cashier
- Paddle support
- Purchase tracking
- Billing history

## 🔐 Access Control

### Service Layer
- `PaymentService` - Payment processing
- `ComponentAccessService` - Access validation

### User Roles
- **Admin**: Full system access
- **User**: Standard user with purchased components

### Permissions
- Free components: Always accessible
- Premium components: Require subscription or purchase
- Templates: Similar access control

## 📊 Analytics

### Admin Dashboard Metrics
- Monthly Recurring Revenue (MRR)
- Total users and active subscribers
- Component downloads
- Sales statistics
- Recent purchases

### API Endpoints
```
GET    /api/v1/components              - List components
GET    /api/v1/components/{slug}       - Component details
GET    /api/v1/components/{slug}/download - Download

GET    /api/v1/templates               - List templates
GET    /api/v1/templates/{slug}        - Template details

POST   /api/v1/purchases               - Create purchase
GET    /api/v1/purchases               - Purchase history

GET    /api/v1/analytics/dashboard     - Dashboard metrics
GET    /api/v1/analytics/revenue       - Revenue data
GET    /api/v1/analytics/downloads     - Download stats
```

## 🛠 Development

### Architecture
```
app/
  Console/Commands/      # CLI tools
  Filament/              # Admin panel
    Pages/              # Filament pages
    Resources/          # CRUD resources
    Widgets/            # Dashboard widgets
  Http/
    Controllers/
      Api/V1/          # API controllers
    Livewire/User/     # User components
  Models/               # Eloquent models
  Services/             # Business logic

database/
  migrations/            # Database schema
  seeders/               # Sample data

resources/
  views/
    layouts/            # Blade layouts
    user/               # User pages
    admin/templates/    # Admin templates
    docs/               # Documentation
```

### Service Layer Pattern

No business logic in controllers:

```php
// PaymentService
public function purchaseItem($user, $type, $id, $amount)
{
    // Validate
    // Process payment
    // Create purchase record
    // Return result
}

// ComponentAccessService
public function canAccessComponent($user, $component)
{
    if ($component->type === 'free') return true;
    if ($user->hasActiveSubscription()) return true;
    return $user->purchased($component);
}
```

### Testing

```bash
php artisan test
```

### Queue Workers

For payment webhooks and async tasks:

```bash
php artisan queue:work
```

## 🎨 Customization

### Theme Engine

Admin settings panel for:
- Primary/Secondary/Accent colors
- Background colors
- Glow intensity (low/medium/high)
- Border radius
- Glass blur amount
- Grid pattern toggle
- Mesh animation toggle

### Adding Components

1. Create Blade component file
2. Add to `components` table
3. Include preview HTML
4. Set category and type
5. Define metadata (price, version)

### Creating Templates

1. Design Admin template layout
2. Add to `templates` table
3. Include preview HTML
4. Set features in metadata
5. Configure pricing

## 🚀 Deployment

### Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=gsm_ui
DB_USERNAME=your_user
DB_PASSWORD=your_password

STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx

SANCTUM_STATEFUL_DOMAINS=your-domain.com
SESSION_DOMAIN=your-domain.com
```

### Queue Workers

Configure supervisor for queue workers:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=forge
numprocs=3
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

### Caching

- Redis for component listings
- Query caching for analytics
- Route caching
- Config caching

```bash
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

### HTTPS

Enforce HTTPS in production:

```php
URL::forceScheme('https');
```

## 🔒 Security

- Sanctum token authentication
- Rate limiting on API
- CSRF protection
- XSS prevention
- SQL injection protection
- CORS configuration
- Security headers
- Regular updates

## 📄 License

Proprietary - All rights reserved

## 🆘 Support

- Documentation: `/docs`
- API Reference: `/docs/api`
- Contact: support@gsm-ui.test

## 🚀 Future Enhancements

- Component versioning
- Rating/review system
- Team/workspace subscriptions
- White-label options
- Component playground
- Automated testing suite
- Documentation generator
- Marketplace for third-party components

## 📈 Roadmap

- Q2 2024: Team workspace features
- Q3 2024: Component marketplace
- Q4 2024: Mobile app
- Q1 2025: Advanced analytics
- Q2 2025: AI-powered recommendations
