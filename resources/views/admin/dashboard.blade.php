@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $total_users ?? 0 }}</div>
            <div class="text-gray-600">Total Users</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-green-600">{{ $total_components ?? 0 }}</div>
            <div class="text-gray-600">Components</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $total_templates ?? 0 }}</div>
            <div class="text-gray-600">Templates</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="text-2xl font-bold text-orange-600">${{ $total_revenue ?? 0 }}</div>
            <div class="text-gray-600">Total Revenue</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Purchases</h2>
            <div class="space-y-4">
                @forelse($recent_purchases ?? [] as $purchase)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">{{ $purchase->user->name ?? 'User' }}</div>
                        <div class="text-sm text-gray-600">{{ $purchase->template->name ?? 'Template' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold">${{ $purchase->amount ?? 0 }}</div>
                        <div class="text-sm text-gray-600">{{ $purchase->created_at->format('M d') }}</div>
                    </div>
                </div>
                @empty
                <p class="text-gray-600 text-center py-4">No recent purchases</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Components</h2>
            <div class="space-y-4">
                @forelse($recent_components ?? [] as $component)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium">{{ $component->name }}</div>
                        <div class="text-sm text-gray-600">{{ ucfirst($component->category ?? '') }}</div>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $component->type == 'free' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ ucfirst($component->type ?? 'free') }}
                    </span>
                </div>
                @empty
                <p class="text-gray-600 text-center py-4">No components</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
