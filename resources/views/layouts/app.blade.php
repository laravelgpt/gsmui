<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GSM-UI Marketplace')</title>
    @vite('resources/css/app.css')
    <script src="{{ mix('js/app.js') }}" defer></script>
    <style>
        :root {
            --bg-primary: #0B0F19;
            --bg-secondary: #131828;
            --bg-card: rgba(19, 24, 40, 0.8);
            --electric-blue: #00D4FF;
            --toxic-green: #39FF14;
            --accent: #6366F1;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --border-color: rgba(0, 212, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Glassmorphism Background */
        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-mesh::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(0, 212, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(57, 255, 20, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
            animation: meshRotate 30s linear infinite;
        }

        @keyframes meshRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Glass Card */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: var(--electric-blue);
            box-shadow: 
                0 12px 48px rgba(0, 212, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        /* Neon Glow Effects */
        .glow-blue {
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }

        .glow-green {
            text-shadow: 0 0 20px rgba(57, 255, 20, 0.5);
        }

        .glow-border {
            position: relative;
        }

        .glow-border::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(45deg, var(--electric-blue), var(--toxic-green));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .glow-border:hover::after {
            opacity: 1;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--electric-blue), var(--accent));
            color: #000;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
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
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        }

        /* Navigation */
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--electric-blue), var(--toxic-green));
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--electric-blue);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        /* Input Styles */
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

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--electric-blue), var(--toxic-green));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--electric-blue);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .animate-pulse-slow {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Grid Pattern Overlay */
        .grid-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="bg-mesh"></div>
    <div class="grid-pattern"></div>

    <!-- Navigation -->
    <nav class="glass-card mx-4 mt-4 p-4 flex items-center justify-between" style="position: relative; z-index: 100;">
        <div class="flex items-center gap-8">
            <a href="/" class="text-2xl font-bold glow-blue">GSM-UI</a>
            <div class="flex gap-6">
                <a href="/" class="nav-link">Home</a>
                <a href="/components" class="nav-link">Components</a>
                <a href="/templates" class="nav-link">Templates</a>
                <a href="/docs" class="nav-link">Documentation</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="/dashboard" class="btn-secondary">Dashboard</a>
                <a href="/logout" class="nav-link">Logout</a>
            @else
                <a href="/login" class="btn-primary">Sign In</a>
                <a href="/register" class="btn-secondary">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 mt-16 py-8 text-center text-gray-500 border-t border-gray-700">
        <p>&copy; 2026 GSM-UI Marketplace. All rights reserved.</p>
    </footer>

    @yield('scripts')
</body>
</html>
