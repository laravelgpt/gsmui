@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Profile</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Account Information</h2>
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Security Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Security</h2>
                <a href="{{ route('user.security') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700">
                    Change Password
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl p-6 text-white mb-6">
                <h3 class="text-lg font-semibold mb-4">My Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Purchases</span>
                        <span class="font-bold">{{ auth()->user()->purchases()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Downloads</span>
                        <span class="font-bold">{{ auth()->user()->downloads_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Member Since</span>
                        <span class="font-bold">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('user.components') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                        My Components
                    </a>
                    <a href="{{ route('user.wishlist') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Wishlist
                    </a>
                    <a href="{{ route('user.notifications') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
