
@extends('layouts.admin')

@section('title', 'Server Node Monitor')

@section('content')
<div class="h-full flex flex-col bg-[#0B0F19]">
    <!-- Header -->
    <div class="h-16 bg-[#131828] border-b border-[rgba(0,212,255,0.2)] flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-[#6366F1] to-[#00D4FF]"></div>
            <span class="text-xl font-bold" style="color: var(--electric-blue)">Server Node Monitor</span>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-sm">
                <span class="text-gray-400">Active Nodes:</span>
                <span class="text-[#39FF14] font-semibold ml-1">12/15</span>
            </div>
            <div class="text-sm">
                <span class="text-gray-400">Average Load:</span>
                <span class="text-[#00D4FF] font-semibold ml-1">67%</span>
            </div>
            <div class="text-sm" id="current-time" class="font-mono text-[#6366F1]">--:--:--</div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-4 gap-4 p-4">
        <div class="glass-card p-4">
            <div class="text-sm text-gray-400 mb-2">CPU Usage</div>
            <div class="text-3xl font-bold text-white">67%</div>
            <div class="mt-3 h-2 bg-[#131828] rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#00D4FF] to-[#6366F1]" style="width: 67%"></div>
            </div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-400 mb-2">Memory</div>
            <div class="text-3xl font-bold text-[#39FF14]">4.2 / 8 GB</div>
            <div class="mt-3 h-2 bg-[#131828] rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#39FF14] to-[#00D4FF]" style="width: 52.5%"></div>
            </div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-400 mb-2">Network I/O</div>
            <div class="text-3xl font-bold text-[#6366F1]">1.2 Gbps</div>
            <div class="text-sm text-gray-400 mt-1">↑ 800 Mbps ↓ 400 Mbps</div>
        </div>
        <div class="glass-card p-4">
            <div class="text-sm text-gray-400 mb-2">Active Connections</div>
            <div class="text-3xl font-bold text-[#F59E0B]">847</div>
            <div class="text-sm text-gray-400 mt-1">+12% from yesterday</div>
        </div>
    </div>

    <!-- Node Grid -->
    <div class="flex-1 p-4 overflow-auto">
        <div class="grid grid-cols-5 gap-4 h-full min-h-0">
            <!-- Node 1 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="url(#grad1)" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="96" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(0,212,255,0.1)"/>
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#00D4FF"/>
                                <stop offset="100%" style="stop-color:#39FF14"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">68%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Alpha</div>
                    <div class="text-xs text-[#39FF14]">Online</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.10</div>
                </div>
            </div>

            <!-- Node 2 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="url(#grad2)" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="198" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(57,255,20,0.1)"/>
                        <defs>
                            <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#39FF14"/>
                                <stop offset="100%" style="stop-color:#00D4FF"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">30%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Beta</div>
                    <div class="text-xs text-[#39FF14]">Online</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.11</div>
                </div>
            </div>

            <!-- Node 3 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="url(#grad3)" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="71" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(99,102,241,0.1)"/>
                        <defs>
                            <linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#6366F1"/>
                                <stop offset="100%" style="stop-color:#00D4FF"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">75%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Gamma</div>
                    <div class="text-xs text-[#39FF14]">Online</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.12</div>
                </div>
            </div>

            <!-- Node 4 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#F59E0B" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="141" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(245,158,11,0.1)"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">50%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Delta</div>
                    <div class="text-xs text-yellow-500">Degraded</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.13</div>
                </div>
            </div>

            <!-- Node 5 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#EF4444" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="57" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(239,68,68,0.1)"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">80%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Epsilon</div>
                    <div class="text-xs text-red-400">Warning</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.14</div>
                </div>
            </div>

            <!-- Node 6 -->
            <div class="glass-card p-4 flex flex-col items-center justify-center">
                <div class="relative mb-3">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#131828" stroke-width="8"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#00D4FF" stroke-width="8" 
                                stroke-dasharray="283" stroke-dashoffset="226" transform="rotate(-90 50 50)"/>
                        <circle cx="50" cy="50" r="35" fill="rgba(0,212,255,0.1)"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-white">20%</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-white">Node Zeta</div>
                    <div class="text-xs text-[#39FF14]">Online</div>
                    <div class="text-xs text-gray-500 mt-1">192.168.1.15</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(1.4); opacity: 0; }
    }
</style>
@endsection
