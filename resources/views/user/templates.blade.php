
@extends('layouts.app')

@section('title', 'Admin Templates')

@section('content')
<div class="min-h-screen">
    <!-- Page Header -->
    <div class="bg-gradient-to-b from-[#0B0F19] to-transparent py-12 mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="text-gradient">Admin Templates</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl">
                10+ pre-built dashboard templates optimized for GSM/Forensic workflows. Ready to deploy.
            </p>
        </div>
    </div>

    <!-- Featured Templates -->
    <div class="max-w-7xl mx-auto px-4 mb-12">
        <h2 class="text-2xl font-bold mb-6">Premium Templates</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Template 1 -->
            <div class="glass-card overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-[#0B0F19] to-[#131828] border-b border-[#00D4FF] flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-20">
                        <div class="w-16 h-16 border-2 border-[#00D4FF] rounded-full absolute top-4 left-4"></div>
                        <div class="w-32 h-8 bg-[#00D4FF] absolute bottom-4 left-4 rounded"></div>
                    </div>
                    <span class="text-[#00D4FF] font-bold">GSM Flasher</span>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg">GSM Flasher Dashboard</h3>
                        <span class="badge badge-premium">$199.99</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Sidebar navigation with terminal output panel for device flashing operations.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="badge badge-free text-xs">Terminal</span>
                        <span class="badge badge-free text-xs">Device Monitor</span>
                        <span class="badge badge-free text-xs">Progress Bar</span>
                    </div>
                    <a href="/templates/gsm-flasher" class="w-full btn-secondary text-center block">Preview Template</a>
                </div>
            </div>

            <!-- Template 2 -->
            <div class="glass-card overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-[#0B0F19] to-[#131828] border-b border-[#39FF14] flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-20">
                        <div class="grid grid-cols-2 gap-2 p-2">
                            <div class="h-20 bg-[#39FF14] rounded"></div>
                            <div class="h-20 bg-[#131828] rounded"></div>
                        </div>
                    </div>
                    <span class="text-[#39FF14] font-bold">Forensic Viewer</span>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg">Forensic Log Viewer</h3>
                        <span class="badge badge-premium">$149.99</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Full-width datagrid with timeline view and evidence tagging system.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="badge badge-free text-xs">Datagrid</span>
                        <span class="badge badge-free text-xs">Timeline</span>
                        <span class="badge badge-free text-xs">Evidence</span>
                    </div>
                    <a href="/templates/forensic-viewer" class="w-full btn-secondary text-center block">Preview Template</a>
                </div>
            </div>

            <!-- Template 3 -->
            <div class="glass-card overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-[#0B0F19] to-[#131828] border-b border-[#6366F1] flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-20 flex items-center justify-center">
                        <div class="w-24 h-24 border-4 border-[#6366F1] rounded-full border-t-transparent animate-spin"></div>
                    </div>
                    <span class="text-[#6366F1] font-bold">Server Monitor</span>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg">Server Node Monitor</h3>
                        <span class="badge badge-premium">$179.99</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Grid of circular progress indicators with real-time sparkline charts.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="badge badge-free text-xs">Progress Orbs</span>
                        <span class="badge badge-free text-xs">Sparklines</span>
                        <span class="badge badge-free text-xs">Node Grid</span>
                    </div>
                    <a href="/templates/server-monitor" class="w-full btn-secondary text-center block">Preview Template</a>
                </div>
            </div>
        </div>
    </div>

    <!-- All Templates Grid -->
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">All Templates (10+)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 glass-card border-l-4 border-l-[#00D4FF]">
                <h4 class="font-bold text-[#00D4FF] mb-2">Network Scanner</h4>
                <p class="text-sm text-gray-400 mb-3">Host discovery, port visualization, vulnerability assessment.</p>
                <div class="text-xs text-[#F59E0B]">$169.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#39FF14]">
                <h4 class="font-bold text-[#39FF14] mb-2">Evidence Management</h4>
                <p class="text-sm text-gray-400 mb-3">Case management, file cataloging, chain of custody.</p>
                <div class="text-xs text-[#F59E0B]">$189.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#6366F1]">
                <h4 class="font-bold text-[#6366F1] mb-2">Signal Analyzer</h4>
                <p class="text-sm text-gray-400 mb-3">Spectrum visualization, strength meters, carrier info.</p>
                <div class="text-xs text-[#F59E0B]">$159.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#EF4444]">
                <h4 class="font-bold text-[#EF4444] mb-2">Incident Response</h4>
                <p class="text-sm text-gray-400 mb-3">Ticketing system, team collaboration, timeline builder.</p>
                <div class="text-xs text-[#F59E0B]">$209.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#F59E0B]">
                <h4 class="font-bold text-[#F59E0B] mb-2">Data Breach Analyzer</h4>
                <p class="text-sm text-gray-400 mb-3">Impact mapping, data classification, notification workflows.</p>
                <div class="text-xs text-[#F59E0B]">$199.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#00D4FF]">
                <h4 class="font-bold text-[#00D4FF] mb-2">Mobile Forensics</h4>
                <p class="text-sm text-gray-400 mb-3">Extraction progress, data categorization, report generation.</p>
                <div class="text-xs text-[#F59E0B]">$229.99</div>
            </div>
            <div class="p-6 glass-card border-l-4 border-l-[#39FF14]">
                <h4 class="font-bold text-[#39FF14] mb-2">SOC Dashboard</h4>
                <p class="text-sm text-gray-400 mb-3">Threat intelligence feeds, alerts aggregation, response coordination.</p>
                <div class="text-xs text-[#F59E0B]">$299.99</div>
            </div>
            <div class="p-6 glass-card opacity-50">
                <h4 class="font-bold text-gray-400 mb-2">Coming Soon...</h4>
                <p class="text-sm text-gray-500 mb-3">Additional templates in development.</p>
            </div>
            <div class="p-6 glass-card opacity-50">
                <h4 class="font-bold text-gray-400 mb-2">Coming Soon...</h4>
                <p class="text-sm text-gray-500 mb-3">Additional templates in development.</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="max-w-4xl mx-auto px-4 mt-16">
        <div class="glass-card p-12 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Need a Custom Template?
            </h2>
            <p class="text-gray-400 text-lg mb-8">
                We can build a custom admin dashboard tailored to your specific forensic workflow requirements.
            </p>
            <a href="/contact" class="btn-primary text-lg">
                Get a Quote
            </a>
        </div>
    </div>
</div>
@endsection
