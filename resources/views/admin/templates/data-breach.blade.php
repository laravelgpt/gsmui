
@extends('layouts.admin')

@section('title', 'Data Breach Analyzer')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#F59E0B] flex items-center px-6">
        <span class="text-xl font-bold text-[#F59E0B]">Data Breach Analyzer</span>
    </div>
    <div class="flex-1 p-4">
        <div class="grid grid-cols-2 gap-4 h-full">
            <div class="glass-card p-4">
                <h3 class="text-[#F59E0B] mb-4">Impact Map</h3>
                <div class="space-y-2">
                    <div class="flex justify-between p-2 bg-[rgba(245,158,11,0.1)] rounded">
                        <span>Database Server</span><span class="text-[#EF4444]">Critical</span>
                    </div>
                </div>
            </div>
            <div class="glass-card p-4">
                <h3 class="text-[#00D4FF] mb-4">Data Classification</h3>
                <div class="space-y-2">
                    <span class="badge badge-premium">PII Exposed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
