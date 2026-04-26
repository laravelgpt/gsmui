<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - GSM-UI')</title>
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-color);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 24px 16px;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--electric-blue), var(--toxic-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-section { margin-bottom: 24px; }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 16px;
            margin-bottom: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(0, 212, 255, 0.1);
            color: var(--electric-blue);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(99, 102, 241, 0.15));
            color: var(--electric-blue);
            border-left: 3px solid var(--electric-blue);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

        .main-content {
            margin-left: 280px;
            padding: 32px;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-secondary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--electric-blue);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 212, 255, 0.1);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .stat-card .stat-icon.blue { background: rgba(0, 212, 255, 0.15); color: var(--electric-blue); }
        .stat-card .stat-icon.green { background: rgba(57, 255, 20, 0.15); color: var(--toxic-green); }
        .stat-card .stat-icon.purple { background: rgba(99, 102, 241, 0.15); color: var(--accent); }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .content-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        .content-card .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-card .card-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .content-card .card-body {
            padding: 24px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--electric-blue), var(--accent));
            color: #000;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--electric-blue);
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            border: 2px solid var(--electric-blue);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(0, 212, 255, 0.1);
        }

        .input-glow {
            background: rgba(11, 15, 25, 0.8);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            color: var(--text-primary);
            width: 100%;
            transition: all 0.3s ease;
        }

        .input-glow:focus {
            outline: none;
            border-color: var(--electric-blue);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tr:hover td {
            background: rgba(0, 212, 255, 0.05);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-free { background: rgba(57, 255, 20, 0.15); color: var(--toxic-green); }
        .badge-premium { background: rgba(0, 212, 255, 0.15); color: var(--electric-blue); }
        .badge-active { background: rgba(57, 255, 20, 0.15); color: var(--toxic-green); }
        .badge-inactive { background: rgba(255, 79, 79, 0.15); color: #ff4f4f; }
        .badge-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; }

        .empty-state {
            text-align: center;
            padding: 64px 32px;
            color: var(--text-secondary);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .tabs {
            display: flex;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
        }

        .tab {
            padding: 12px 24px;
            color: var(--text-secondary);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: all 0.3s ease;
        }

        .tab:hover {
            color: var(--electric-blue);
        }

        .tab.active {
            color: var(--electric-blue);
            border-bottom-color: var(--electric-blue);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .card-row {
            display: flex;
            gap: 24px;
            margin-bottom: 24px;
        }

        .card-row > * {
            flex: 1;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                padding: 24px 8px;
            }

            .sidebar-brand h1,
            .nav-item span,
            .nav-section-title {
                display: none;
            }

            .nav-item {
                justify-content: center;
                padding: 12px;
            }

            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .card-row {
                flex-direction: column;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
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
            <h1>GSM-UI</h1>
        </div>

        <nav>
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="{{ url('/admin') }}" class="nav-item {{ request()->is('admin') || request()->is('admin/*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ url('/admin/components') }}" class="nav-item {{ request()->is('admin/components*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                        <polyline points="2 17 12 22 22 17"/>
                        <polyline points="2 12 12 17 22 12"/>
                    </svg>
                    <span>Components</span>
                </a>
                <a href="{{ url('/admin/templates') }}" class="nav-item {{ request()->is('admin/templates*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span>Templates</span>
                </a>
                <a href="{{ url('/admin/purchases') }}" class="nav-item {{ request()->is('admin/purchases*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <span>Sales</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Settings</div>
                <a href="{{ url('/admin/settings') }}" class="nav-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Theme Engine</span>
                </a>
                <a href="{{ url('/admin/users') }}" class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Users</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
