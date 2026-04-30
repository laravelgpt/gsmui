@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f12]">
    <!-- Header -->
    <header class="border-b border-white/10 bg-[#0f0f12]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">Prompt Gallery</span>
            </div>
            <nav class="flex items-center gap-6">
                <a href="/" class="text-gray-400 hover:text-white transition-colors">Components</a>
                <a href="/templates" class="text-gray-400 hover:text-white transition-colors">Templates</a>
                <a href="/chatui" class="text-gray-400 hover:text-white transition-colors">Chat</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="py-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Premium UI
                <span class="block bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 bg-clip-text text-transparent">
                    Prompt Library
                </span>
            </h1>
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
                Discover and copy production-ready prompts for React, Vue, and modern frameworks. Curated by top developers.
            </p>
            
            <!-- Search -->
            <div class="relative max-w-xl mx-auto">
                <input type="text" 
                       id="prompt-search"
                       placeholder="Search prompts... (e.g. dashboard, glassmorphism, button)"
                       class="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                       value="{{ $search }}">
                <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="px-6 mb-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Category -->
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-sm">Category:</span>
                    <div class="flex flex-wrap gap-2">
                        <a href="?category=all&framework={{ $framework }}" 
                           class="category-filter px-4 py-2 rounded-full text-sm font-medium transition-all {{ $category == 'all' ? 'bg-purple-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                            All
                        </a>
                        @foreach($categories as $cat)
                        <a href="?category={{ $cat }}&framework={{ $framework }}"
                           class="category-filter px-4 py-2 rounded-full text-sm font-medium transition-all {{ $category == $cat ? 'bg-purple-500 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                            {{ ucfirst($cat) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Framework -->
                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-gray-400 text-sm">Framework:</span>
                    <select id="framework-filter" class="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-purple-500">
                        <option value="all" {{ $framework == 'all' ? 'selected' : '' }}>All Frameworks</option>
                        @foreach($frameworks as $fw)
                        <option value="{{ $fw }}" {{ $framework == $fw ? 'selected' : '' }}>{{ ucfirst($fw) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    @if(count($featuredPrompts) > 0)
    <section class="px-6 mb-16">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                Featured This Week
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredPrompts as $prompt)
                <a href="/prompt-gallery/{{ $prompt['id'] }}" 
                   class="group block bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 overflow-hidden hover:border-purple-500/50 hover:shadow-2xl hover:shadow-purple-500/10 transition-all">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                {{ ucfirst($prompt['framework']) }}
                            </span>
                            <span class="text-gray-500 text-sm">{{ $prompt['category'] }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-purple-400 transition-colors">
                            {{ $prompt['title'] }}
                        </h3>
                        <p class="text-gray-400 text-sm line-clamp-2">{{ $prompt['description'] }}</p>
                        <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                Featured
                            </span>
                            <span>by {{ $prompt['author'] }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Grid -->
    <section class="px-6 pb-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="prompt-grid">
                @foreach($prompts as $prompt)
                <div class="prompt-card bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 overflow-hidden hover:border-purple-500/50 transition-all hover:shadow-2xl hover:shadow-purple-500/5">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $prompt['featured'] ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'bg-white/10 text-gray-400' }}">
                                    {{ ucfirst($prompt['framework']) }}
                                </span>
                                <span class="text-gray-500 text-sm">{{ $prompt['category'] }}</span>
                            </div>
                            @if($prompt['featured'])
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2 hover:text-purple-400 transition-colors cursor-pointer" onclick="window.location.href='/prompt-gallery/{{ $prompt['id'] }}'">
                            {{ $prompt['title'] }}
                        </h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $prompt['description'] }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 border-2 border-[#0f0f12] flex items-center justify-center text-xs text-white">
                                    {{ substr($prompt['author'], 0, 1) }}
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $prompt['author'] }}</span>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-white/5 border-t border-white/10">
                        <button onclick="copyPrompt({{ $prompt['id'] }}, this)" 
                                class="w-full py-2 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy Prompt
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if(count($prompts) === 0)
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl text-white mb-2">No prompts found</h3>
                <p class="text-gray-400">Try adjusting your search or filter criteria</p>
            </div>
            @endif
        </div>
    </section>
</div>

@section('scripts')
<script>
// Search functionality
document.getElementById('prompt-search').addEventListener('input', function(e) {
    const query = e.target.value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', query);
    url.searchParams.set('category', document.querySelector('.category-filter.bg-purple-500')?.textContent.trim().toLowerCase() || 'all');
    url.searchParams.set('framework', document.getElementById('framework-filter').value);
    window.location.href = url.toString();
});

// Framework filter
document.getElementById('framework-filter').addEventListener('change', function(e) {
    const url = new URL(window.location.href);
    url.searchParams.set('framework', e.target.value);
    url.searchParams.set('category', document.querySelector('.category-filter.bg-purple-500')?.textContent.trim().toLowerCase() || 'all');
    url.searchParams.delete('search');
    window.location.href = url.toString();
});

// Category filter
document.querySelectorAll('.category-filter').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = new URL(window.location.href);
        url.searchParams.set('category', this.textContent.trim().toLowerCase());
        url.searchParams.set('framework', document.getElementById('framework-filter').value);
        url.searchParams.delete('search');
        window.location.href = url.toString();
    });
});

// Copy prompt
async function copyPrompt(id, button) {
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
            // Copy to clipboard
            await navigator.clipboard.writeText(data.prompt);
            
            const originalText = button.innerHTML;
            button.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Copied!
            `;
            button.style.background = '#10b981';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
            }, 2000);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
