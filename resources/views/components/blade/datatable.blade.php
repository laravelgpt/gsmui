
@props(['columns', 'items', 'filters', 'sortable', 'actions', 'config'])

<div class="datatable-component" x-data="datatableConfig()">
    <!-- Filters Bar -->
    @if(!empty($filters))
        <div class="datatable-filters mb-4 p-4 bg-[#131828] rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($filters as $field => $filter)
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">{{ ucfirst($field) }}</label>
                        @if($filter['type'] === 'select')
                            <select x-model="filters.{{ $field }}" class="input-glow w-full">
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" 
                                   x-model="filters.{{ $field }}"
                                   placeholder="Filter {{ ucfirst($field) }}"
                                   class="input-glow w-full">
                        @endif
                    </div>
                @endforeach
                <div class="flex items-end">
                    <button @click="applyFilters" class="btn-primary w-full">Apply Filters</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="datatable-overflow overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr class="border-b border-[rgba(0,212,255,0.1)]">
                    @foreach($columns as $column)
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-400 uppercase">
                            @if(in_array($column['field'], $sortable ?? []))
                                <button @click="sortBy('{{ $column['field'] }}')" 
                                        class="flex items-center gap-2 hover:text-[#00D4FF]">
                                    {{ $column['label'] }}
                                    <span x-text="sortIcon('{{ $column['field'] }}')"></span>
                                </button>
                            @else
                                {{ $column['label'] }}
                            @endif
                        </th>
                    @endforeach
                    @if(!empty($actions))
                        <th class="py-3 px-4 text-right text-sm font-semibold text-gray-400 uppercase">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-b border-[rgba(0,212,255,0.05)] hover:bg-[rgba(0,212,255,0.02)] transition-colors">
                        @foreach($columns as $column)
                            <td class="py-3 px-4">
                                @isset($column['formatter'])
                                    {!! $column['formatter']($item) !!}
                                @else
                                    {{ data_get($item, $column['field']) }}
                                @endisset
                            </td>
                        @endforeach
                        @if(!empty($actions))
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @foreach($actions as $action)
                                        @if($action['type'] === 'button')
                                            <button class="btn-secondary text-sm py-1 px-2">
                                                {{ $action['name'] }}
                                            </button>
                                        @elseif($action['type'] === 'dropdown')
                                            <div class="relative">
                                                <button class="btn-secondary text-sm py-1 px-2">
                                                    ⋮
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + (empty($actions) ? 0 : 1) }}" 
                            class="py-8 text-center text-gray-500">
                            No records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="datatable-pagination mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries
            </div>
            <div class="flex gap-1">
                {{ $items->links() }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function datatableConfig() {
        return {
            sortField: '{{ request("sort") }}',
            sortDirection: '{{ request("direction", "asc") }}',
            filters: {},
            
            sortBy(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
                
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.sortField);
                url.searchParams.set('direction', this.sortDirection);
                window.location.href = url.toString();
            },
            
            sortIcon(field) {
                if (this.sortField !== field) return '↕';
                return this.sortDirection === 'asc' ? '↑' : '↓';
            },
            
            applyFilters() {
                const url = new URL(window.location.href);
                Object.keys(this.filters).forEach(key => {
                    if (this.filters[key]) {
                        url.searchParams.set(key, this.filters[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
                window.location.href = url.toString();
            }
        };
    }
</script>
@endpush
