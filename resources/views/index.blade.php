@extends('layouts.app')

@section('title', 'GSM-UI Marketplace - Premium UI Components & Admin Templates')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
        <div class="grid-pattern"></div>
        <div class="relative z-10 text-center max-w-5xl mx-auto px-4">
            <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                <span class="inline-block px-4 py-2 rounded-full border border-[rgba(0,212,255,0.3)] bg-[rgba(0,212,255,0.05)] text-[#00D4FF] text-sm font-medium mb-6 glow-blue">
                    🚀 Premium UI Library for GSM/Forensic Applications
                </span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <span class="glow-blue">Midnight Electric</span><br>
                <span class="glow-green">Admin Templates</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-400 mb-8 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.5s;">
                High-contrast dark interfaces with glassmorphism effects, designed specifically for data-heavy GSM/Forensic web applications.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up" style="animation-delay: 0.7s;">
                <a href="/components" class="btn-primary text-lg">
                    🎨 Browse Components
                </a>
                <a href="/templates" class="btn-secondary text-lg">
                    📄 View Templates
                </a>
                <a href="/docs" class="btn-secondary text-lg">
                    📚 Documentation
                </a>
            </div>
            
            <div class="mt-12 flex justify-center gap-8 text-gray-500 animate-fade-in-up" style="animation-delay: 0.9s;">
                <div class="text-center">
                    <div class="text-3xl font-bold glow-blue">50+</div>
                    <div class="text-sm">UI Components</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold glow-green">10+</div>
                    <div class="text-sm">Admin Templates</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold" style="color: var(--accent);">1000+</div>
                    <div class="text-sm">Downloads</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Built for Data-Heavy Applications</h2>
                <p class="text-gray-400 text-lg">Specifically designed for GSM/Forensic interfaces that require clarity, precision, and advanced data visualization.</p>
            </div>
            
            <div class="grid-3">
                <div class="glass-card p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-xl flex items-center justify-center" style="background: rgba(0, 212, 255, 0.15);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00D4FF" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <line x1="3" y1="9" x2="21" y2="9"/>
                            <line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Data Grid Pro</h3>
                    <p class="text-gray-400">Advanced tables with sorting, filtering, pagination, and inline editing for forensic data analysis.</p>
                </div>
                
                <div class="glass-card p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-xl flex items-center justify-center" style="background: rgba(57, 255, 20, 0.15);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#39FF14" stroke-width="2">
                            <path d="M12 20V10"/>
                            <path d="M18 20V4"/>
                            <path d="M6 20v-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Real-time Charts</h3>
                    <p class="text-gray-400">Sparklines and progress indicators for live data monitoring and signal strength visualization.</p>
                </div>
                
                <div class="glass-card p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-xl flex items-center justify-center" style="background: rgba(99, 102, 241, 0.15);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Filter Systems</h3>
                    <p class="text-gray-400">Complex multi-select filters with search and tag-based filtering for querying large datasets.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Templates Grid -->
    <section class="py-20 relative z-10" style="background: rgba(11, 15, 25, 0.5);">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Premium Admin Templates</h2>
                <p class="text-gray-400 text-lg">10+ specialized dashboard layouts for GSM/Forensic workflows.</p>
            </div>
            
            <div class="grid-3 gap-6">
                <div class="glass-card overflow-hidden">
                    <div style="height: 180px; background: linear-gradient(135deg, #0B0F19, #131828); border-bottom: 1px solid var(--border-color); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 12px; left: 12px; right: 12px;">
                            <div style="height: 8px; background: rgba(0, 212, 255, 0.3); border-radius: 4px; width: 60px;"></div>
                        </div>
                        <div style="position: absolute; bottom: 12px; left: 12px; right: 12px;">
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: rgba(0, 212, 255, 0.2); border-radius: 4px;"></div>
                                <div style="width: 40px; height: 20px; background: rgba(57, 255, 20, 0.2); border-radius: 4px;"></div>
                                <div style="width: 30px; height: 20px; background: rgba(99, 102, 241, 0.2); border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">GSM Flasher Dashboard</h3>
                        <p class="text-gray-400 text-sm mb-4">Sidebar + terminal output panel</p>
                        <span class="badge badge-premium">Premium</span>
                    </div>
                </div>
                
                <div class="glass-card overflow-hidden">
                    <div style="height: 180px; background: linear-gradient(135deg, #0B0F19, #131828); border-bottom: 1px solid var(--border-color); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: 2fr 1fr; gap: 12px; padding: 12px;">
                            <div style="background: rgba(0, 212, 255, 0.1); border-radius: 6px;"></div>
                            <div style="background: rgba(99, 102, 241, 0.1); border-radius: 6px;"></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Forensic Log Viewer</h3>
                        <p class="text-gray-400 text-sm mb-4">Full-width datagrid with timeline</p>
                        <span class="badge badge-premium">Premium</span>
                    </div>
                </div>
                
                <div class="glass-card overflow-hidden">
                    <div style="height: 180px; background: linear-gradient(135deg, #0B0F19, #131828); border-bottom: 1px solid var(--border-color); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; gap: 20px;">
                        <div style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--electric-blue); position: relative;">
                            <div style="position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; border-radius: 50%; border: 3px solid transparent; border-top-color: var(--electric-blue); animation: rotate 2s linear infinite;"></div>
                        </div>
                        <div style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--toxic-green); position: relative;">
                            <div style="position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; border-radius: 50%; border: 3px solid transparent; border-top-color: var(--toxic-green); animation: rotate 3s linear infinite;"></div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Server Node Monitor</h3>
                        <p class="text-gray-400 text-sm mb-4">Circular progress orbs & sparklines</p>
                        <span class="badge badge-premium">Premium</span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="/templates" class="btn-primary">
                    View All 10+ Templates
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative z-10">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="glass-card p-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Ready to Build Forensic Interfaces?
                </h2>
                <p class="text-gray-400 text-lg mb-8">
                    Join hundreds of developers using GSM-UI to create high-contrast, data-heavy applications with our Midnight Electric theme.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/docs/installation" class="btn-primary text-lg">
                        🚀 Get Started Now
                    </a>
                    <a href="/components" class="btn-secondary text-lg">
                        🔍 Explore Components
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    @keyframes rotate {
        to { transform: rotate(360deg); }
    }
</style>
