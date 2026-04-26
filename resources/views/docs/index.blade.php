@extends('layouts.docs')

@section('content')
    <!-- Breadcrumb -->
    <nav class="docs-breadcrumb">
        <a href="/">Home</a> / 
        <span class="text-gray-400">Documentation</span>
    </nav>

    <h1>GSM-UI Documentation</h1>

    <p class="lead">
        Welcome to the GSM-UI SaaS Marketplace documentation. This comprehensive guide will help you build premium UI components and admin templates specifically tailored for data-heavy, GSM/Forensic web applications.
    </p>

    <div class="grid-2" style="margin: 40px 0;">
        <div class="docs-note">
            <h4>🚀 Quick Start</h4>
            <p>Get up and running in minutes with our CLI tool and pre-built components.</p>
            <ul style="margin-top: 12px;">
                <li><a href="/docs/installation">Installation Guide</a></li>
                <li><a href="/docs/cli">CLI Tool Usage</a></li>
                <li><a href="/docs/components/overview">Component Library</a></li>
            </ul>
        </div>

        <div class="docs-warning">
            <h4>🎨 Midnight Electric Theme</h4>
            <p>Built with high-contrast dark mode, deep space backgrounds, and glowing neon accents.</p>
            <ul style="margin-top: 12px;">
                <li><a href="/docs/customization/theme">Theme Engine</a></li>
                <li><a href="/docs/customization/components">Building Components</a></li>
            </ul>
        </div>
    </div>

    <h2>📚 Documentation Sections</h2>

    <div class="grid-3" style="margin-top: 24px;">
        <a href="/docs/installation" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">🚀 Getting Started</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Installation, configuration, and initial setup for the GSM-UI platform.</p>
        </a>

        <a href="/docs/components/overview" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">🎨 Components</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Browse and use our extensive library of data-heavy UI components.</p>
        </a>

        <a href="/docs/templates/gsm-flashing" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">📄 Templates</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Full admin dashboard templates for specific GSM/Forensic use cases.</p>
        </a>

        <a href="/docs/api/components" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">🔌 API Reference</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Complete API documentation for components, templates, and purchases.</p>
        </a>

        <a href="/docs/customization/theme" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">🎛️ Theme Engine</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Customize the Midnight Electric theme to match your brand.</p>
        </a>

        <a href="/docs/customization/components" class="glass-card" style="padding: 24px; text-decoration: none;">
            <h3 style="color: var(--electric-blue); margin-bottom: 12px;">🛠️ Building Components</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Learn how to create and contribute your own components.</p>
        </a>
    </div>

    <h2 style="margin-top: 60px;">🔍 Key Features</h2>

    <div class="card-row">
        <div class="glass-card" style="padding: 24px;">
            <h4 style="color: var(--electric-blue); margin-bottom: 12px;">TALL Stack Architecture</h4>
            <p style="color: var(--text-secondary);">Built on Laravel 13, Livewire 4, Alpine.js, and Tailwind CSS 4 for maximum performance and developer experience.</p>
        </div>

        <div class="glass-card" style="padding: 24px;">
            <h4 style="color: var(--toxic-green); margin-bottom: 12px;">Service Layer Pattern</h4>
            <p style="color: var(--text-secondary);">Business logic encapsulated in Service Classes for maintainability and testability.</p>
        </div>

        <div class="glass-card" style="padding: 24px;">
            <h4 style="color: var(--accent); margin-bottom: 12px;">CLI Integration</h4>
            <p style="color: var(--text-secondary);">Download components directly to your project with <code>php artisan gsm:add {component}</code>.</p>
        </div>
    </div>

    <h2 style="margin-top: 40px;">📊 Admin Panel Capabilities</h2>

    <table class="docs-table">
        <thead>
            <tr>
                <th>Feature</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Real-time Dashboard</strong></td>
                <td>MRR, user metrics, component downloads, and sales analytics</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
            <tr>
                <td><strong>Component Manager</strong></td>
                <td>CRUD interface for managing UI components</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
            <tr>
                <td><strong>Template Manager</strong></td>
                <td>CRUD interface for admin dashboard templates</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
            <tr>
                <td><strong>Theme Engine</strong></td>
                <td>Adjust Midnight Electric variables globally</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
            <tr>
                <td><strong>Purchase Management</strong></td>
                <td>Track all component and template purchases</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
            <tr>
                <td><strong>User Management</strong></td>
                <td>Manage user accounts and subscriptions</td>
                <td><span class="badge badge-active">Active</span></td>
            </tr>
        </tbody>
    </table>

    <h2 style="margin-top: 40px;">🎯 10+ Admin Templates</h2>

    <div class="grid-2">
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">1. GSM Flasher Dashboard</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Sidebar + terminal output</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">2. Forensic Log Viewer</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Full-width datagrid focus</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">3. Server Node Monitor</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Circular progress orbs & sparklines</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">4. Network Scanner</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Host discovery & port visualization</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">5. Evidence Management</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Case management & custody chain</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">6. Signal Analyzer</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Spectrum visualization & meters</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">7. Incident Response</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Ticketing & timeline reconstruction</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">8. Data Breach Analyzer</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Impact mapping & notification flows</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">9. Mobile Forensics</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Extraction & categorization panels</p>
        </div>
        <div class="glass-card" style="padding: 20px;">
            <h4 style="color: var(--electric-blue);">10. SOC Dashboard</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 8px;">Threat feeds & response coordination</p>
        </div>
    </div>

    <h2 style="margin-top: 60px; margin-bottom: 24px;">💡 Next Steps</h2>

    <div class="glass-card" style="padding: 32px;">
        <ol style="color: var(--text-secondary); line-height: 2;">
            <li>1. Complete the <a href="/docs/installation" style="color: var(--electric-blue);">Installation</a> guide</li>
            <li>2. Set up your Stripe/Paddle integration for monetization</li>
            <li>3. Configure the <a href="/docs/cli" style="color: var(--electric-blue);">CLI tool</a> for component downloads</li>
            <li>4. Customize the <a href="/docs/customization/theme" style="color: var(--electric-blue);">Theme Engine</a> to match your brand</li>
            <li>5. Start building or customizing components using the <a href="/docs/components/overview" style="color: var(--electric-blue);">Component Guide</a></li>
            <li>6. Explore the <a href="/docs/templates/gsm-flashing" style="color: var(--electric-blue);">Template Library</a> for dashboard designs</li>
        </ol>
    </div>
@endsection
