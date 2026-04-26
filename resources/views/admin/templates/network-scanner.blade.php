
@extends('layouts.admin')

@section('title', 'Network Scanner')

@section('content')
<div class="h-full flex flex-col">
    <div class="h-16 bg-[#0B0F19] border-b border-[#00D4FF] flex items-center px-6">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full border-2 border-[#00D4FF]"></div>
            <span class="text-xl font-bold glow-blue">Network Scanner</span>
        </div>
    </div>
    <div class="flex-1 grid grid-cols-3 gap-4 p-4">
        <div class="col-span-2 glass-card p-4">
            <canvas id="networkCanvas" class="w-full h-full"></canvas>
        </div>
        <div class="glass-card p-4">
            <h3 class="text-[#00D4FF] mb-4">Active Hosts</h3>
            <div class="space-y-2">
                <div class="flex justify-between p-2 bg-[rgba(0,212,255,0.1)] rounded">
                    <span>192.168.1.1</span><span class="text-[#39FF14]">●</span>
                </div>
                <div class="flex justify-between p-2 bg-[rgba(0,212,255,0.1)] rounded">
                    <span>192.168.1.15</span><span class="text-[#39FF14]">●</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
