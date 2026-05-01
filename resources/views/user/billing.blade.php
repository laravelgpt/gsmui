@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">{{ ucfirst(str_replace('-', ' ', billing)) }}</h1>
    <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
        <div class="text-6xl mb-4">🔧</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Coming Soon</h3>
        <p class="text-gray-600">This feature is under development.</p>
    </div>
</div>
@endsection
