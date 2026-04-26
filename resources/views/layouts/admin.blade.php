
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - GSM-UI Admin</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --bg-primary: #0B0F19;
            --bg-secondary: #131828;
            --electric-blue: #00D4FF;
            --toxic-green: #39FF14;
            --accent: #6366F1;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --border-color: rgba(0, 212, 255, 0.2);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(19, 24, 40, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--electric-blue), var(--accent));
            color: #000;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-secondary {
            background: transparent;
            color: var(--electric-blue);
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid var(--electric-blue);
            cursor: pointer;
            font-weight: 600;
        }
        .input-glow {
            background: rgba(11, 15, 25, 0.8);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 8px 12px;
            color: var(--text-primary);
        }
        .badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-free { background: rgba(57,255,20,0.15); color: var(--toxic-green); }
        .badge-premium { background: rgba(0,212,255,0.15); color: var(--electric-blue); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: var(--electric-blue); border-radius: 3px; }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
