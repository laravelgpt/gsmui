
@extends('layouts.admin')

@section('title', 'GSM Flasher Dashboard')

@section('content')
<div class="h-full flex flex-col">
    <!-- Top Bar -->
    <div class="h-16 bg-[#0B0F19] border-b border-[rgba(0,212,255,0.2)] flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-[#00D4FF] to-[#6366F1]"></div>
            <span class="text-xl font-bold glow-blue">GSM Flasher</span>
            <span class="text-xs px-3 py-1 rounded-full bg-[rgba(57,255,20,0.15)] text-[#39FF14]">● LIVE</span>
        </div>
        <div class="flex items-center gap-6 text-sm text-gray-400">
            <span class="flex items-center gap-2"><span class="w-2 h-2 bg-[#39FF14] rounded-full animate-pulse"></span>Device Connected</span>
            <span>Session: ABC-123</span>
            <span id="clock" class="font-mono text-[#00D4FF]">--:--:--</span>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-[#131828] border-r border-[rgba(0,212,255,0.1)] p-4 flex flex-col">
            <nav class="space-y-2">
                <a href="#" class="nav-item active">Dashboard</a>
                <a href="#" class="nav-item">Devices</a>
                <a href="#" class="nav-item">Flash Queue</a>
                <a href="#" class="nav-item">Logs</a>
                <a href="#" class="nav-item">Settings</a>
            </nav>
            
            <div class="mt-auto pt-4 border-t border-[rgba(0,212,255,0.1)]">
                <div class="text-xs text-gray-500 mb-2">Connected Devices</div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#00D4FF]"></div>
                        <span class="text-sm">Device Alpha</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#39FF14]"></div>
                        <span class="text-sm">Device Beta</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Stats Row -->
            <div class="grid grid-cols-4 gap-4 p-4">
                <div class="glass-card p-4">
                    <div class="text-sm text-gray-400 mb-1">Signal Strength</div>
                    <div class="text-2xl font-bold text-[#00D4FF]">87%</div>
                    <div class="mt-2 h-1 bg-[#131828] rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#00D4FF] to-[#6366F1]" style="width: 87%"></div>
                    </div>
                </div>
                <div class="glass-card p-4">
                    <div class="text-sm text-gray-400 mb-1">Flash Progress</div>
                    <div class="text-2xl font-bold text-[#39FF14]">64%</div>
                    <div class="mt-2 h-1 bg-[#131828] rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#39FF14] to-[#00D4FF]" style="width: 64%"></div>
                    </div>
                </div>
                <div class="glass-card p-4">
                    <div class="text-sm text-gray-400 mb-1">Active Time</div>
                    <div class="text-2xl font-bold text-[#6366F1]">2h 14m</div>
                </div>
                <div class="glass-card p-4">
                    <div class="text-sm text-gray-400 mb-1">Data Written</div>
                    <div class="text-2xl font-bold text-[#F59E0B]">128 MB</div>
                </div>
            </div>

            <!-- Terminal & Monitor -->
            <div class="flex-1 grid grid-cols-3 gap-4 p-4 min-h-0">
                <!-- Main Terminal -->
                <div class="col-span-2 glass-card flex flex-col overflow-hidden">
                    <div class="flex items-center justify-between p-3 bg-[#0d1117] border-b border-[rgba(0,212,255,0.1)]">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#ff5f57]"></span>
                            <span class="w-2 h-2 rounded-full bg-[#febc2e]"></span>
                            <span class="w-2 h-2 rounded-full bg-[#28c840]"></span>
                            <span class="text-xs text-gray-400 ml-3">Terminal</span>
                        </div>
                        <span class="text-xs text-gray-500">/dev/ttyUSB0</span>
                    </div>
                    <div class="flex-1 p-4 overflow-y-auto font-mono text-sm bg-black" id="terminal-output">
                        <div class="terminal-line"><span class="text-[#61AFEF]">[INFO]</span> <span class="text-gray-400">Initializing GSM flasher...</span></div>
                        <div class="terminal-line"><span class="text-[#61AFEF]">[INFO]</span> <span class="text-gray-400">Device detected: Qualcomm X60</span></div>
                        <div class="terminal-line"><span class="text-[#61AFEF]">[INFO]</span> <span class="text-gray-400">Negotiating protocol...</span></div>
                        <div class="terminal-line"><span class="text-[#39FF14]">[OK]</span> <span class="text-gray-400">Protocol v2.1 established</span></div>
                        <div class="terminal-line"><span class="text-[#61AFEF]">[INFO]</span> <span class="text-gray-400">Reading device memory...</span></div>
                        <div class="terminal-line animate-pulse"><span class="text-[#E5C07B]">[PROG]</span> <span class="text-gray-400">Sector 0x08000000: 64% complete</span></div>
                    </div>
                    <div class="p-3 border-t border-[rgba(0,212,255,0.1)] bg-[#0d1117]">
                        <input type="text" placeholder="Enter command..." class="w-full bg-transparent border-none outline-none text-gray-300 font-mono text-sm" />
                    </div>
                </div>

                <!-- Side Monitor -->
                <div class="glass-card p-4 flex flex-col gap-4">
                    <div class="text-sm font-semibold text-gray-300 border-b border-[rgba(0,212,255,0.2)] pb-2">Device Monitor</div>
                    
                    <div class="space-y-4">
                        <div class="circular-progress">
                            <svg width="120" height="120" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#131828" stroke-width="8"/>
                                <circle cx="60" cy="60" r="50" fill="none" stroke="url(#grad)" stroke-width="8" 
                                        stroke-dasharray="314" stroke-dashoffset="107" transform="rotate(-90 60 60)"/>
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" style="stop-color:#00D4FF"/>
                                        <stop offset="100%" style="stop-color:#39FF14"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">64%</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="p-3 rounded-lg bg-[rgba(0,212,255,0.1)]">
                                <div class="text-[#00D4FF] text-lg font-bold">1.2V</div>
                                <div class="text-xs text-gray-400">Voltage</div>
                            </div>
                            <div class="p-3 rounded-lg bg-[rgba(57,255,20,0.1)]">
                                <div class="text-[#39FF14] text-lg font-bold">42°C</div>
                                <div class="text-xs text-gray-400">Temperature</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Flash Process</span>
                                <span class="text-[#39FF14]">64%</span>
                            </div>
                            <div class="h-2 bg-[#131828] rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#00D4FF] to-[#39FF14]" style="width: 64%"></div>
                            </div>
                        </div>

                        <button class="w-full py-2 rounded-lg bg-gradient-to-r from-[#00D4FF] to-[#6366F1] text-black font-semibold hover:opacity-90 transition-opacity">
                            Emergency Stop
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .circular-progress {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    .terminal-line {
        margin-bottom: 4px;
        animation: fadeInUp 0.3s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
