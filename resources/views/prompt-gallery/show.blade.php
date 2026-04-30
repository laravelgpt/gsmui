@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f12]">
    <!-- Header -->
    <header class="border-b border-white/10 bg-[#0f0f12]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/prompt-gallery" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Prompt Gallery</span>
                </a>
            </div>
            <nav class="flex items-center gap-6">
                <a href="/" class="text-gray-400 hover:text-white transition-colors">Components</a>
                <a href="/templates" class="text-gray-400 hover:text-white transition-colors">Templates</a>
                <a href="/chatui" class="text-gray-400 hover:text-white transition-colors">Chat</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Preview Panel -->
            <div class="lg:col-span-2">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="/prompt-gallery" class="hover:text-purple-400 transition-colors">Gallery</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-purple-400">{{ $prompt['title'] }}</span>
                </div>

                <!-- Title & Meta -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $prompt['title'] }}</h1>
                        <p class="text-gray-400 text-lg">{{ $prompt['description'] }}</p>
                    </div>
                    @if($prompt['featured'])
                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30 flex-shrink-0">
                        Featured
                    </span>
                    @endif
                </div>

                <!-- Tags -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-400 text-sm">
                        {{ ucfirst($prompt['framework']) }}
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-400 text-sm">
                        {{ ucfirst($prompt['category']) }}
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-400 text-sm">
                        {{ $prompt['complexity'] }}
                    </span>
                    @foreach($prompt['tags'] as $tag)
                    <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-400 text-sm">
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>

                <!-- Author & Date -->
                <div class="flex items-center gap-4 mb-8 p-4 bg-white/5 rounded-xl border border-white/10">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                        {{ substr($prompt['author'], 0, 1) }}
                    </div>
                    <div>
                        <div class="text-white font-medium">{{ $prompt['author'] }}</div>
                        <div class="text-gray-400 text-sm">{{ $prompt['created_at'] }}</div>
                    </div>
                </div>

                <!-- Code Preview -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-white">Code Preview</h2>
                        <button onclick="copyPromptCode()" 
                                class="px-4 py-2 rounded-xl bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium transition-all flex items-center gap-2"
                                id="copyBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="copyIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l4 4m0 0l-4 4m4-4H4" stroke="none"/>
                            </svg>
                            <span id="copyText">Copy Code</span>
                        </button>
                    </div>
                    <pre class="rounded-xl overflow-hidden"><code class="language-{{ $prompt['language'] }} bg-[#0a0f12] p-6 block text-gray-300 text-sm leading-relaxed">{{ htmlspecialchars($prompt['content']) }}</code></pre>
                </div>

                <!-- Description -->
                <div class="prose prose-invert max-w-none">
                    <h2 class="text-xl font-semibold text-white mb-4">Implementation Guide</h2>
                    <div class="text-gray-300 space-y-4">
                        <p>This prompt generates a modern, production-ready component with best practices:</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-400">
                            <li>Uses modern JavaScript/TypeScript syntax</li>
                            <li>Implements responsive design principles</li>
                            <li>Follows accessibility guidelines (a11y)</li>
                            <li>Optimized for performance</li>
                            <li>Includes proper error handling</li>
                        </ul>
                        <p>Customize the generated code to fit your specific needs and integrate it into your existing codebase.</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="sticky top-24">
                    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
                        <button onclick="copyPrompt({{ $prompt['id'] }})" 
                                class="w-full py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2 mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l4 4m0 0l-4 4m4-4H4"/>
                            </svg>
                            Copy to Clipboard
                        </button>
                        <a href="/chatui" 
                           class="w-full py-3 px-6 rounded-xl bg-white/10 border border-white/10 text-white font-semibold hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Use in Chat
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Stats</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Framework</span>
                                <span class="text-white">{{ ucfirst($prompt['framework']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Category</span>
                                <span class="text-white">{{ ucfirst($prompt['category']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Complexity</span>
                                <span class="text-white">{{ ucfirst($prompt['complexity']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Lines of Code</span>
                                <span class="text-white">{{ substr_count($prompt['content'], "\n") + 1 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Related Prompts -->
                    @if(count($relatedPrompts) > 0)
                    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Related Prompts</h3>
                        <div class="space-y-3">
                            @foreach($relatedPrompts as $related)
                            <a href="/prompt-gallery/{{ $related['id'] }}" 
                               class="block p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all border border-transparent hover:border-purple-500/30">
                                <h4 class="text-white font-medium text-sm mb-1">{{ $related['title'] }}</h4>
                                <span class="text-xs text-gray-400">{{ ucfirst($related['framework']) }} · {{ ucfirst($related['category']) }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

@section('scripts')
<script>
// Copy full prompt code
function copyPromptCode() {
    const code = document.querySelector('.language-{{ $prompt["language"] }}').textContent;
    const btn = document.getElementById('copyBtn');
    const icon = document.getElementById('copyIcon');
    const text = document.getElementById('copyText');
    
    navigator.clipboard.writeText(code).then(() => {
        // Show success state
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
        text.textContent = 'Copied!';
        btn.style.background = '#10b981';
        
        setTimeout(() => {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l4 4m0 0l-4 4m4-4H4" stroke="none"/>';
            text.textContent = 'Copy Code';
            btn.style.background = '';
        }, 2000);
    });
}

// Copy to chat
async function copyPrompt(id) {
    try {
        const response = await fetch(`/prompt-gallery/${id}/copy`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            await navigator.clipboard.writeText(data.prompt);
            alert('Prompt copied to clipboard! Paste in chat to use.');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

<style>
.language-php, .language-jsx, .language-vue, .language-html, .language-css, .language-js, .language-ts, .language-tsx {
    font-family: 'Fira Code', 'Monaco', 'Consolas', monospace !important;
}
</style>
@endsection
