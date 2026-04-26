<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Documentation - GSM-UI')</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --bg-primary: #0B0F19;
            --bg-secondary: #131828;
            --bg-card: rgba(19, 24, 40, 0.9);
            --electric-blue: #00D4FF;
            --toxic-green: #39FF14;
            --accent: #6366F1;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --border-color: rgba(0, 212, 255, 0.2);
            --code-bg: #0d1117;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* Docs Sidebar */
        .docs-sidebar {
            width: 280px;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-color);
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            padding: 24px 16px;
        }

        .docs-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .docs-logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--electric-blue), var(--toxic-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .docs-nav h4 {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 24px 0 12px;
        }

        .docs-nav a {
            display: block;
            padding: 10px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .docs-nav a:hover {
            background: rgba(0, 212, 255, 0.1);
            color: var(--electric-blue);
        }

        .docs-nav a.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(99, 102, 241, 0.15));
            color: var(--electric-blue);
            border-left: 3px solid var(--electric-blue);
        }

        /* Docs Content */
        .docs-content {
            margin-left: 280px;
            padding: 40px 60px;
            max-width: 900px;
        }

        .docs-breadcrumb {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 24px;
        }

        .docs-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--electric-blue), var(--toxic-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .docs-content h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 40px 0 20px;
            color: var(--electric-blue);
        }

        .docs-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 32px 0 16px;
            color: var(--toxic-green);
        }

        .docs-content p {
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .docs-content code {
            font-family: 'Courier New', Courier, monospace;
            background: var(--code-bg);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.9em;
            color: var(--toxic-green);
            border: 1px solid rgba(57, 255, 20, 0.2);
        }

        .docs-content pre {
            background: var(--code-bg);
            border-radius: 12px;
            padding: 24px;
            overflow-x: auto;
            margin: 24px 0;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }

        .docs-content pre code {
            background: none;
            padding: 0;
            border: none;
            color: var(--text-primary);
        }

        .docs-content ul, .docs-content ol {
            margin-bottom: 20px;
            padding-left: 32px;
        }

        .docs-content li {
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .docs-note {
            background: rgba(0, 212, 255, 0.1);
            border-left: 4px solid var(--electric-blue);
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 24px 0;
        }

        .docs-note h4 {
            color: var(--electric-blue);
            margin-bottom: 8px;
        }

        .docs-warning {
            background: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin: 24px 0;
        }

        .docs-warning h4 {
            color: #ffc107;
            margin-bottom: 8px;
        }

        .docs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
        }

        .docs-table th,
        .docs-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .docs-table th {
            background: rgba(0, 212, 255, 0.1);
            color: var(--electric-blue);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .docs-table tr:last-child td {
            border-bottom: none;
        }

        .cli-example {
            background: var(--code-bg);
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid rgba(57, 255, 20, 0.2);
        }

        .cli-example .prompt {
            color: var(--toxic-green);
            font-weight: 600;
            margin-right: 8px;
        }

        .cli-example code {
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .docs-sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .docs-content {
                margin-left: 0;
                padding: 24px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="docs-sidebar">
        <div class="docs-logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                <rect x="4" y="4" width="24" height="24" rx="4" stroke="url(#grad1)" stroke-width="2"/>
                <path d="M12 16L16 20L20 12" stroke="url(#grad1)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#00D4FF;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#6366F1;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
            <h1>GSM-UI Docs</h1>
        </div>

        <nav class="docs-nav">
            <h4>Getting Started</h4>
            <a href="/docs" class="{{ request()->is('docs') || request()->is('docs/') ? 'active' : '' }}">Introduction</a>
            <a href="/docs/installation" class="{{ request()->is('docs/installation*') ? 'active' : '' }}">Installation</a>
            <a href="/docs/cli" class="{{ request()->is('docs/cli*') ? 'active' : '' }}">CLI Tool</a>

            <h4>Components</h4>
            <a href="/docs/components/overview">Overview</a>
            <a href="/docs/components/data-display">Data Display</a>
            <a href="/docs/components/filters">Filters</a>
            <a href="/docs/components/actions">Actions</a>

            <h4>Templates</h4>
            <a href="/docs/templates/gsm-flashing">GSM Flasher</a>
            <a href="/docs/templates/forensic-viewer">Forensic Log Viewer</a>
            <a href="/docs/templates/server-monitor">Server Node Monitor</a>

            <h4>API Reference</h4>
            <a href="/docs/api/components">Components API</a>
            <a href="/docs/api/templates">Templates API</a>
            <a href="/docs/api/purchases">Purchases API</a>

            <h4>Customization</h4>
            <a href="/docs/customization/theme">Theme Engine</a>
            <a href="/docs/customization/components">Building Components</a>
        </nav>
    </aside>

    <!-- Content -->
    <main class="docs-content">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
