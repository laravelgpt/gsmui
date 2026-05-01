<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GSM UI') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">GSM UI</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/components') }}" class="text-gray-600 hover:text-gray-900">Components</a>
                    <a href="{{ url('/templates') }}" class="text-gray-600 hover:text-gray-900">Templates</a>
                    <a href="{{ url('/docs') }}" class="text-gray-600 hover:text-gray-900">Docs</a>
                    <a href="{{ url('/chatui') }}" class="text-gray-600 hover:text-gray-900">Chat</a>
                    <a href="{{ url('/prompt-gallery') }}" class="text-gray-600 hover:text-gray-900">Gallery</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        @can('isAdmin')
                            <a href="{{ url('/admin') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg">Admin</a>
                        @endcan
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 ml-2">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="py-8">
        @yield('content')
    </main>
</body>
</html>
