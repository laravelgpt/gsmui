<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Component Manager</h1>
            <p class="text-gray-600 mt-1">Manage all UI components in the system</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="bulkActivate" wire:loading.attr="disabled" 
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition" 
                    @if(empty($selectedItems)) disabled @endif>
                Activate Selected
            </button>
            <button wire:click="bulkDeactivate" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition"
                    @if(empty($selectedItems)) disabled @endif>
                Deactivate Selected
            </button>
            <button wire:click="bulkDelete" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition"
                    @if(empty($selectedItems)) disabled @endif>
                Delete Selected
            </button>
            <button wire:click="create" 
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Component
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" wire:model.debounce.300ms="search"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Search components...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select wire:model="category" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select wire:model="type" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="$refresh" 
                    class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    @if(count($selectedItems) > 0)
    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <input type="checkbox" wire:model="selectAll" id="select-all" 
                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
            <label for="select-all" class="text-sm text-gray-700">
                {{ count($selectedItems) }} item(s) selected
            </label>
        </div>
        <div class="flex gap-2">
            <button wire:click="bulkActivate" 
                class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200 transition">
                Activate
            </button>
            <button wire:click="bulkDeactivate" 
                class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-sm hover:bg-yellow-200 transition">
                Deactivate
            </button>
            <button wire:click="bulkDelete" wire:confirm="Delete selected components?"
                class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200 transition">
                Delete
            </button>
        </div>
    </div>
    @endif

    <!-- Components Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" wire:model="selectAll" 
                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Component</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Category</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Price</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($components as $component)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" wire:model="selectedItems.{{ $component->id }}" 
                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($component->preview_url)
                                <img src="{{ $component->preview_url }}" alt="" 
                                    class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 
                                    flex items-center justify-center text-white font-bold">
                                    {{ substr($component->name, 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $component->name }}</div>
                                    <div class="text-sm text-gray-500 line-clamp-1">{{ $component->description ?? 'No description' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ ucfirst($component->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($component->type === 'free')
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Free</span>
                            @elseif($component->type === 'paid')
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">Paid</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-700">Premium</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($component->type === 'free')
                            <span class="text-green-600 font-medium">Free</span>
                            @else
                            ${{ number_format($component->price ?? 0, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($component->is_active)
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Active</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="edit({{ $component->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $component->id }})" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $components->firstItem() }} to {{ $components->lastItem() }} of {{ $components->total() }} components
                </div>
                <div class="flex gap-1">
                    {{ $components->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:ignore>
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:ignore>
            <!-- Modal Header -->
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $componentId ? 'Edit Component' : 'Create New Component' }}
                </h3>
                <button wire:click="closeModal" 
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <input type="text" wire:model="componentCategory"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('componentCategory') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select wire:model="componentType"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="free">Free</option>
                            <option value="paid">Paid</option>
                            <option value="premium">Premium</option>
                        </select>
                        @error('componentType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                        <input type="number" wire:model="price" step="0.01" min="0"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="0.00">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Preview URL -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview URL</label>
                        <input type="url" wire:model="preview_url"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="https://example.com/preview">
                        @error('preview_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- File Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Component File</label>
                        <input type="file" wire:model="file"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if($file)
                        <p class="text-sm text-gray-500 mt-1">Selected: {{ $file->getClientOriginalName() }}</p>
                        @endif
                    </div>
                    
                    <!-- Tags -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <input type="text" wire:model="tags"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="ui, button, interactive (comma separated)">
                    </div>
                    
                    <!-- Compatibility -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Framework Compatibility</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['React', 'Vue', 'Svelte', 'Angular', 'Livewire', 'Blade'] as $framework)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="compatibility" value="{{ $framework }}"
                                    class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                <span class="text-sm text-gray-700">{{ $framework }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea wire:model="description" rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Describe the component's functionality and usage..."></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active"
                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                            <span class="text-sm text-gray-700">Component is active and visible</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="p-6 border-t bg-gray-50 flex justify-end gap-3">
                <button wire:click="closeModal" 
                    class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $componentId ? 'Update' : 'Create' }} Component
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $selectedComponent)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:ignore>
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Component</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $selectedComponent->name }}"? This action cannot be undone.</p>
                <div class="flex gap-3 justify-center">
                    <button wire:click="closeModal" 
                        class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button wire:click="confirmDelete" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-refresh on event
    Livewire.on('refreshTable', () => {
        window.livewire.emit('refreshComponentList');
    });

    // Show success/error notifications
    Livewire.on('success', (message) => {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });

    Livewire.on('error', (message) => {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
</script>

<style>
    @keyframes slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slide-in { animation: slide-in 0.3s ease-out; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush
