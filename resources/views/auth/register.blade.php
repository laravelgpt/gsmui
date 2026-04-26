
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="glass-card p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#39FF14] to-[#00D4FF] flex items-center justify-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Create Account</h1>
                <p class="text-gray-400 mt-2">Start building with GSM-UI</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.2)]">
                    <p class="text-[#EF4444] text-sm">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-[rgba(57,255,20,0.1)] border border-[rgba(57,255,20,0.2)]">
                    <p class="text-[#39FF14] text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <input type="text" name="name" required
                        class="input-glow w-full" placeholder="John Doe">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" required
                        class="input-glow w-full" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" required minlength="8"
                        class="input-glow w-full" placeholder="Create a strong password">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="input-glow w-full" placeholder="Confirm your password">
                </div>
                <div class="text-sm text-gray-400">
                    By signing up, you agree to our <a href="/docs" class="text-[#00D4FF]">Terms of Service</a>.
                </div>
                <button type="submit" class="w-full btn-primary py-3 mt-4">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400">
                    Already have an account? 
                    <a href="/login" class="text-[#00D4FF] hover:text-[#39FF14] font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
