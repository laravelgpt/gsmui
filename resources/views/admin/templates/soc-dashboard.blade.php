
@extends('layouts.admin')

@section('title', 'SOC Dashboard')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#00D4FF] flex items-center px-6">
        <span class="text-xl font-bold glow-blue">Security Operations Center</span>
    </div>
    <div class="flex-1 p-4">
        <div class="grid grid-cols-12 gap-4 h-full">
            <div class="col-span-8 glass-card p-4">
                <h3 class="text-[#00D4FF] mb-4">Threat Intelligence Feed</h3>
                <div class="space-y-2" id="threat-feed">
                    <div class="flex justify-between p-2 bg-[rgba(0,212,255,0.1)] rounded">
                        <span>Malicious IP: 1.2.3.4</span>
                        <span class="text-[#39FF14]">Just now</span>
                    </div>
                </div>
            </div>
            <div class="col-span-4 glass-card p-4">
                <h3 class="text-[#00D4FF] mb-4">Metrics</h3>
                <div class="space-y-4">
                    <div class="text-center p-4 bg-[rgba(0,212,255,0.1)] rounded">
                        <div class="text-2xl font-bold">42</div>
                        <div class="text-sm text-gray-400">Active Alerts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
