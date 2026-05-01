@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">My Components</h1>
        <a href="{{ url('/components') }}" class="text-purple-600 hover:text-purple-700">Browse All Components</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($purchases ?? [] as $purchase)
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold">{{ $purchase->component->name ?? 'Component' }}</h3>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $purchase->component->type == 'free' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ ucfirst($purchase->component->type ?? 'free') }}
                    </span>
                </div>
                <p class="text-gray-600 text-sm mb-4">{{ $purchase->component->description ?? '' }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Purchased: {{ $purchase->created_at->format('M d, Y') }}</span>
                    <a href="{{ route('user.components.download', $purchase->component->id) }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">Download</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No components yet</h3>
            <p class="text-gray-600 mb-6">Browse and purchase components to get started</p>
            <a href="{{ url('/components') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">Browse Components</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
