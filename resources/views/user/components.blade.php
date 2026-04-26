
@extends('layouts.app')

@section('title', 'Components Library')

@section('content')
<div class="min-h-screen">
    <!-- Page Header -->
    <div class="bg-gradient-to-b from-[#0B0F19] to-transparent py-12 mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="text-gradient">Components Library</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl">
                Browse and download 50+ premium UI components designed for forensic and data-intensive applications.
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 mb-8">
        <div class="glass-card p-6">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Search Components</label>
                    <input type="text" id="search-input" placeholder="Search by name or description..." 
                           class="input-glow w-full" />
                </div>
                <div class="w-full lg:w-48">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                    <select id="category-filter" class="input-glow w-full">
                        <option value="">All Categories</option>
                        <option value="data-display">Data Display</option>
                        <option value="filters">Filters</option>
                        <option value="actions">Actions</option>
                        <option value="forms">Forms</option>
                        <option value="navigation">Navigation</option>
                        <option value="feedback">Feedback</option>
                        <option value="layout">Layout</option>
                    </select>
                </div>
                <div class="w-full lg:w-48">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Type</label>
                    <select id="type-filter" class="input-glow w-full">
                        <option value="">All Types</option>
                        <option value="free">Free</option>
                        <option value="premium">Premium</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Components Grid -->
    <div class="max-w-7xl mx-auto px-4 mb-12">
        <div id="components-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Components will be loaded here -->
        </div>
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-[#00D4FF] border-t-transparent"></div>
            <p class="text-gray-400 mt-4">Loading components...</p>
        </div>
    </div>
</div>

<!-- Component Detail Modal -->
<div id="component-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="glass-card max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <div id="modal-content"></div>
    </div>
</div>

<script>
    let components = [];
    let userPurchases = [];

    // Fetch components
    fetch('/api/v1/components')
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            components = data.data;
            renderComponents(components);
        });

    // Fetch user purchases
    @auth
        fetch('/api/v1/purchases', {
            headers: { 'Authorization': 'Bearer {{ auth()->user()->api_token }}' }
        })
        .then(r => r.json())
        .then(data => {
            userPurchases = data.data?.data || [];
        });
    @endauth

    function renderComponents(comps) {
        const grid = document.getElementById('components-grid');
        grid.innerHTML = comps.map(comp => `
            <div class="glass-card overflow-hidden hover:scale-[1.02] transition-transform duration-300">
                <div class="h-32 bg-gradient-to-br from-[#0B0F19] to-[#131828] border-b border-[rgba(0,212,255,0.1)] flex items-center justify-center">
                    ${comp.preview_html || '<div class="text-gray-500">No preview</div>'}
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-bold text-lg">${comp.name}</h3>
                        <span class="badge ${comp.type === 'free' ? 'badge-free' : 'badge-premium'}">
                            ${comp.type === 'free' ? 'Free' : 'Premium'}
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm mb-3 line-clamp-2">${comp.description}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                        <span>${comp.category}</span>
                        <span>•</span>
                        <span>${comp.download_count || 0} downloads</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="viewComponent(${comp.id})" 
                                class="flex-1 btn-secondary text-sm py-2">
                            View Details
                        </button>
                        @auth
                            @if(auth()->user()->has_active_subscription || true)
                                <button onclick="downloadComponent(${comp.id})" 
                                        class="btn-primary text-sm py-2 px-3">
                                    Download
                                </button>
                            @else
                                <button onclick="purchaseComponent(${comp.id})" 
                                        class="btn-primary text-sm py-2 px-3">
                                    ${comp.type === 'free' ? 'Download' : 'Buy'}
                                </button>
                            @endif
                        @else
                            <button onclick="window.location='/login'" 
                                    class="btn-primary text-sm py-2 px-3">
                                Login to Download
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Filter handlers
    document.getElementById('search-input').addEventListener('input', filterComponents);
    document.getElementById('category-filter').addEventListener('change', filterComponents);
    document.getElementById('type-filter').addEventListener('change', filterComponents);

    function filterComponents() {
        const search = document.getElementById('search-input').value.toLowerCase();
        const category = document.getElementById('category-filter').value;
        const type = document.getElementById('type-filter').value;

        const filtered = components.filter(comp => {
            const matchSearch = !search || 
                comp.name.toLowerCase().includes(search) || 
                comp.description.toLowerCase().includes(search);
            const matchCategory = !category || comp.category === category;
            const matchType = !type || comp.type === type;
            return matchSearch && matchCategory && matchType;
        });

        renderComponents(filtered);
    }

    function viewComponent(id) {
        const comp = components.find(c => c.id === id);
        const modal = document.getElementById('component-modal');
        const content = document.getElementById('modal-content');
        
        content.innerHTML = `
            <h2 class="text-2xl font-bold mb-4">${comp.name}</h2>
            <p class="text-gray-400 mb-6">${comp.description}</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="text-gray-400">Category</span>
                    <p class="font-semibold">${comp.category}</p>
                </div>
                <div>
                    <span class="text-gray-400">Type</span>
                    <p class="font-semibold">${comp.type}</p>
                </div>
            </div>

            <h3 class="font-semibold mb-3 text-[#00D4FF]">Preview</h3>
            <div class="bg-[#0d1117] rounded-lg p-4 mb-6 min-h-[200px]">
                ${comp.preview_html || '<p class="text-gray-500">No preview available</p>'}
            </div>

            <h3 class="font-semibold mb-3 text-[#00D4FF]">Code</h3>
            <div class="bg-[#0d1117] rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-gray-300 font-mono"><code>${escapeHtml(comp.code_snippet)}</code></pre>
            </div>

            <div class="mt-6 flex gap-3">
                @auth
                    @if(auth()->user()->has_active_subscription)
                        <button onclick="downloadComponent(${comp.id})" class="btn-primary">
                            Download Component
                        </button>
                    @else
                        <button onclick="purchaseComponent(${comp.id})" class="btn-primary">
                            ${comp.type === 'free' ? 'Download Free' : 'Purchase ($' + (comp.metadata?.price || '49.99') + ')'} 
                        </button>
                    @endif
                @else
                    <button onclick="window.location='/login'" class="btn-primary">
                        Login to Download
                    </button>
                @endauth
                <button onclick="closeModal()" class="btn-secondary">Close</button>
            </div>
        `;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('component-modal').classList.add('hidden');
        document.getElementById('component-modal').classList.remove('flex');
    }

    function downloadComponent(id) {
        @auth
            window.location = `/api/v1/components/${id}/download?token={{ auth()->user()->api_token }}`;
        @else
            window.location = '/login';
        @endauth
    }

    function purchaseComponent(id) {
        @auth
            if (confirm('Purchase this component?')) {
                fetch(`/api/v1/purchases`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer {{ auth()->user()->api_token }}'
                    },
                    body: JSON.stringify({ purchasable_type: 'component', purchasable_id: id })
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        alert('Purchase completed!');
                        location.reload();
                    }
                });
            }
        @else
            window.location = '/login';
        @endauth
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close modal on outside click
    document.getElementById('component-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection
