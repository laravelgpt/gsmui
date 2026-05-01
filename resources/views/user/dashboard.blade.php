@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">User Dashboard</h1>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $purchases_count ?? 0 }}</div>
            <div class="text-gray-600">Total Purchases</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-green-600">${{ $total_spent ?? 0 }}</div>
            <div class="text-gray-600">Total Spent</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $components_count ?? 0 }}</div>
            <div class="text-gray-600">Components</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-orange-600">{{ $templates_count ?? 0 }}</div>
            <div class="text-gray-600">Templates</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        <div class="space-y-4">
            @forelse($recent_activity ?? [] as $activity)
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold mr-4">
                    {{ substr($activity['type'] ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="font-medium">{{ $activity['description'] ?? 'Activity' }}</div>
                    <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($activity['created_at'] ?? now())->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <p class="text-gray-600 text-center py-8">No recent activity</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('user.components') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-lg transition text-center">
            <div class="text-3xl mb-2">📦</div>
            <div class="font-semibold">My Components</div>
        </a>
        <a href="{{ route('user.wishlist') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-lg transition text-center">
            <div class="text-3xl mb-2">❤️</div>
            <div class="font-semibold">Wishlist</div>
        </a>
        <a href="{{ route('user.billing') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-lg transition text-center">
            <div class="text-3xl mb-2">💳</div>
            <div class="font-semibold">Billing History</div>
        </a>
        <a href="{{ route('user.security') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-lg transition text-center">
            <div class="text-3xl mb-2">🔒</div>
            <div class="font-semibold">Security</div>
        </a>
    </div>
</div>
@endsection
