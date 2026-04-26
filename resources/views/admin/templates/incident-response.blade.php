
@extends('layouts.admin')

@section('title', 'Incident Response')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#EF4444] flex items-center px-6">
        <span class="text-xl font-bold text-[#EF4444]">Incident Response Console</span>
    </div>
    <div class="flex-1 grid grid-cols-3 gap-4 p-4">
        <div class="glass-card p-4">
            <h3 class="text-[#EF4444] mb-4">Active Tickets</h3>
            <div class="space-y-2">
                <div class="p-3 bg-[rgba(239,68,68,0.1)] rounded">
                    <div class="font-semibold">INC-1234</div>
                    <div class="text-sm text-gray-400">Data Breach Detected</div>
                </div>
            </div>
        </div>
        <div class="col-span-2 glass-card p-4">
            <h3 class="text-[#00D4FF] mb-4">Timeline</h3>
            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="w-32 text-sm text-gray-400">14:32:00</div>
                    <div class="flex-1 p-3 bg-[rgba(239,68,68,0.1)] rounded">Breach detected</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
