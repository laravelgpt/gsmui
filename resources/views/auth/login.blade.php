
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="glass-card p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#00D4FF] to-[#6366F1] flex items-center justify-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <rect x="4" y="4" width="18" height="18" rx="4"/>
                        <path d="M12 16L16 20L20 12"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Sign In</h1>
                <p class="text-gray-400 mt-2">Welcome back to GSM-UI</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.2)]">
                    <p class="text-[#EF4444] text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" required
                        class="input-glow w-full" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="input-glow w-full" placeholder="Enter your password">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="accent-[#00D4FF]">
                        <span class="text-sm text-gray-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-[#00D4FF] hover:text-[#39FF14]">Forgot password?</a>
                </div>
                <button type="submit" class="w-full btn-primary py-3">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    Don't have an account? 
                    <a href="/register" class="text-[#00D4FF] hover:text-[#39FF14] font-medium">Sign up</a>
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-[rgba(0,212,255,0.1)]">
                <div class="text-center">
                    <p class="text-xs text-gray-500">Demo credentials:</p>
                    <p class="text-xs text-[#00D4FF]">admin@gsm-ui.test / password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
