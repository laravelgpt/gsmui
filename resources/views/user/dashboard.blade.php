
@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative py-20">
        <div class="grid-pattern"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                <span class="text-gradient">Welcome Back</span>
            </h1>
            <p class="text-xl text-gray-400 mb-8">Your personal workspace for GSM/Forensic components</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="max-w-7xl mx-auto px-4 -mt-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="glass-card p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl flex items-center justify-center" style="background: rgba(0,212,255,0.15);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00D4FF" stroke-width="2">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-white" id="stat-components">0</div>
                <div class="text-gray-400 mt-1">Components Access</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl flex items-center justify-center" style="background: rgba(57,255,20,0.15);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#39FF14" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M9 9h6v6H9z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-white" id="stat-templates">0</div>
                <div class="text-gray-400 mt-1">Templates Owned</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.15);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2">
                        <path d="M12 2v20M2 12h20"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-white" id="stat-downloads">0</div>
                <div class="text-gray-400 mt-1">Total Downloads</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.15);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2">
                        <path d="M12 2a10 10 0 0 1 10 10"/>
                        <path d="M12 2a10 10 0 0 0-10 10"/>
                        <path d="M12 2a10 10 0 0 1-10-10"/>
                        <path d="M12 2a10 10 0 0 0 10-10"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-white" id="stat-subscribed">
                    @if(auth()->user()->has_active_subscription)
                        <span class="text-[#39FF14]">●</span> Active
                    @else
                        <span class="text-gray-400">No</span>
                    @endif
                </div>
                <div class="text-gray-400 mt-1">Subscription Status</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 pb-20">
        <!-- Quick Actions -->
        <div class="glass-card p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="/components" class="p-6 border-2 border-dashed border-[rgba(0,212,255,0.3)] rounded-xl hover:border-[#00D4FF] hover:bg-[rgba(0,212,255,0.05)] transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center group-hover:bg-[rgba(0,212,255,0.25)] transition-colors">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00D4FF" stroke-width="2">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Browse Components</h3>
                            <p class="text-sm text-gray-400">50+ UI components available</p>
                        </div>
                    </div>
                </a>
                <a href="/templates" class="p-6 border-2 border-dashed border-[rgba(57,255,20,0.3)] rounded-xl hover:border-[#39FF14] hover:bg-[rgba(57,255,20,0.05)] transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[rgba(57,255,20,0.15)] flex items-center justify-center group-hover:bg-[rgba(57,255,20,0.25)] transition-colors">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#39FF14" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">View Templates</h3>
                            <p class="text-sm text-gray-400">10+ premium dashboards</p>
                        </div>
                    </div>
                </a>
                <a href="/docs/installation" class="p-6 border-2 border-dashed border-[rgba(99,102,241,0.3)] rounded-xl hover:border-[#6366F1] hover:bg-[rgba(99,102,241,0.05)] transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[rgba(99,102,241,0.15)] flex items-center justify-center group-hover:bg-[rgba(99,102,241,0.25)] transition-colors">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4M12 8h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Read Docs</h3>
                            <p class="text-sm text-gray-400">Complete usage guides</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity & Library -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- My Library -->
            <div class="lg:col-span-2">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">My Library</h2>
                        <a href="/components" class="text-[#00D4FF] hover:text-[#39FF14] transition-colors text-sm">View All →</a>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="library-grid">
                        <div class="p-4 rounded-xl bg-[rgba(0,212,255,0.05)] border border-[rgba(0,212,255,0.1)]">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[rgba(0,212,255,0.15)] flex items-center justify-center flex-shrink-0">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00D4FF" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">Data Grid Pro</h4>
                                    <p class="text-sm text-gray-400">Premium</p>
                                    <button class="mt-2 text-xs text-[#00D4FF] hover:text-[#39FF14]">Use Component →</button>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-[rgba(57,255,20,0.05)] border border-[rgba(57,255,20,0.1)]">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[rgba(57,255,20,0.15)] flex items-center justify-center flex-shrink-0">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#39FF14" stroke-width="2">
                                        <path d="M12 20V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">Log Viewer Panel</h4>
                                    <p class="text-sm text-[#39FF14]">Free</p>
                                    <button class="mt-2 text-xs text-[#39FF14] hover:text-[#00D4FF]">Use Component →</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="glass-card p-6">
                <h2 class="text-2xl font-bold mb-6">Account</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between py-3 border-b border-[rgba(0,212,255,0.1)]">
                        <span class="text-gray-400">Plan</span>
                        @if(auth()->user()->has_active_subscription)
                            <span class="text-[#39FF14] font-semibold">Pro Active</span>
                        @else
                            <span class="text-gray-400">Free Tier</span>
                        @endif
                    </div>
                    <div class="flex justify-between py-3 border-b border-[rgba(0,212,255,0.1)]">
                        <span class="text-gray-400">API Access</span>
                        <span class="text-[#00D4FF]">Enabled</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="text-gray-400">Components Owned</span>
                        <span class="text-white font-semibold" id="owned-count">0</span>
                    </div>
                </div>

                <div class="space-y-3">
                    @if(!auth()->user()->has_active_subscription)
                        <a href="/billing" class="w-full btn-primary text-center block">
                            Upgrade to Pro
                        </a>
                    @endif
                    <a href="/api-tokens" class="w-full btn-secondary text-center block">
                        Generate API Token
                    </a>
                    <a href="/billing/history" class="w-full btn-secondary text-center block">
                        Billing History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update stats dynamically
    document.addEventListener('DOMContentLoaded', function() {
        const stats = {
            components: 50,
            templates: 10,
            downloads: 1247
        };
        
        animateValue('stat-components', 0, stats.components, 1000);
        animateValue('stat-templates', 0, stats.templates, 1000);
        animateValue('stat-downloads', 0, stats.downloads, 1000);
    });
    
    function animateValue(id, start, end, duration) {
        const el = document.getElementById(id);
        if (!el) return;
        const range = end - start;
        const minTimer = 50;
        const stepTime = Math.abs(Math.floor(duration / range));
        const obj = el;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * range + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
</script>
@endsection
