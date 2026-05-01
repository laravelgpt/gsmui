@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">{{ ucfirst(str_replace(['-', '_'], ' ', str_replace('/admin/', '', request()->path()))) }}</h1>
    <div class="bg-white rounded-xl shadow-sm border p-8">
        <p class="text-gray-600">Content for {{ request()->path() }}</p>
    </div>
</div>
@endsection
