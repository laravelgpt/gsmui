<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EnhancedComponentController extends Controller
{
    /**
     * List all components with advanced filtering
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|in:free,paid,premium',
            'active' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'framework' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'sort_by' => 'nullable|in:name,price,created_at,popularity',
            'sort_order' => 'nullable|in:asc,desc',
            'limit' => 'nullable|integer|between:1,100',
        ]);

        $cacheKey = 'components_' . md5(json_encode($validated));
        
        $components = Cache::remember($cacheKey, 3600, function () use ($validated) {
            $query = Component::query()->withCount(['downloads', 'purchases']);

            // Search
            if ($validated['search'] ?? false) {
                $query->where(function ($q) use ($validated) {
                    $q->where('name', 'like', '%' . $validated['search'] . '%')
                      ->orWhere('description', 'like', '%' . $validated['search'] . '%')
                      ->orWhere('category', 'like', '%' . $validated['search'] . '%');
                });
            }

            // Filters
            if ($validated['category'] ?? false) {
                $query->where('category', $validated['category']);
            }

            if ($validated['type'] ?? false) {
                $query->where('type', $validated['type']);
            }

            if ($validated['active'] ?? false) {
                $query->where('is_active', true);
            }

            if ($validated['framework'] ?? false) {
                $query->whereJsonContains('compatibility', $validated['framework']);
            }

            if ($validated['tags'] ?? false) {
                $query->where(function ($q) use ($validated) {
                    foreach ($validated['tags'] as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                });
            }

            if (isset($validated['min_price'])) {
                $query->where('price', '>=', $validated['min_price']);
            }

            if (isset($validated['max_price'])) {
                $query->where('price', '<=', $validated['max_price']);
            }

            // Sorting
            $sortBy = $validated['sort_by'] ?? 'created_at';
            $sortOrder = $validated['sort_order'] ?? 'desc';
            
            if ($sortBy === 'popularity') {
                $query->orderByRaw('(downloads_count + purchases_count) ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            return $query->paginate($validated['limit'] ?? 20);
        });

        return response()->json([
            'success' => true,
            'data' => $components->items(),
            'meta' => [
                'current_page' => $components->currentPage(),
                'last_page' => $components->lastPage(),
                'per_page' => $components->perPage(),
                'total' => $components->total(),
            ],
        ]);
    }

    /**
     * Show single component
     */
    public function show(Component $component)
    {
        $component->loadCount(['downloads', 'purchases']);
        
        return response()->json([
            'success' => true,
            'data' => $component,
        ]);
    }

    /**
     * Create new component
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:components',
            'description' => 'required|string|min:10',
            'category' => 'required|string|max:100',
            'type' => 'required|in:free,paid,premium',
            'price' => 'nullable|numeric|min:0',
            'preview_url' => 'nullable|url|max:2048',
            'file' => 'nullable|file|max:20480',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'compatibility' => 'nullable|array',
            'compatibility.*' => 'string|in:React,Vue,Svelte,Angular,Livewire,Blade',
            'is_active' => 'boolean',
        ]);

        $componentData = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'price' => $validated['type'] === 'free' ? 0 : ($validated['price'] ?? 0),
            'preview_url' => $validated['preview_url'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'tags' => $validated['tags'] ?? [],
            'compatibility' => $validated['compatibility'] ?? [],
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store(
                'components/' . strtolower(str_replace(' ', '-', $validated['name'])),
                'public'
            );
            $componentData['file_path'] = $path;
        }

        $component = Component::create($componentData);

        // Clear cache
        Cache::tags(['components'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Component created successfully',
            'data' => $component,
        ], 201);
    }

    /**
     * Update component
     */
    public function update(Request $request, Component $component)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:components,name,' . $component->id,
            'description' => 'sometimes|string|min:10',
            'category' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:free,paid,premium',
            'price' => 'nullable|numeric|min:0',
            'preview_url' => 'nullable|url|max:2048',
            'file' => 'nullable|file|max:20480',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'compatibility' => 'nullable|array',
            'compatibility.*' => 'string|in:React,Vue,Svelte,Angular,Livewire,Blade',
            'is_active' => 'boolean',
        ]);

        $componentData = [];

        if (isset($validated['name'])) {
            $componentData['name'] = $validated['name'];
        }
        if (isset($validated['description'])) {
            $componentData['description'] = $validated['description'];
        }
        if (isset($validated['category'])) {
            $componentData['category'] = $validated['category'];
        }
        if (isset($validated['type'])) {
            $componentData['type'] = $validated['type'];
        }
        if (isset($validated['price'])) {
            $componentData['price'] = $validated['type'] === 'free' ? 0 : $validated['price'];
        }
        if (isset($validated['preview_url'])) {
            $componentData['preview_url'] = $validated['preview_url'];
        }
        if (isset($validated['is_active'])) {
            $componentData['is_active'] = $validated['is_active'];
        }
        if (isset($validated['tags'])) {
            $componentData['tags'] = $validated['tags'];
        }
        if (isset($validated['compatibility'])) {
            $componentData['compatibility'] = $validated['compatibility'];
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($component->file_path && Storage::disk('public')->exists($component->file_path)) {
                Storage::disk('public')->delete($component->file_path);
            }

            $path = $request->file('file')->store(
                'components/' . strtolower(str_replace(' ', '-', $component->name)),
                'public'
            );
            $componentData['file_path'] = $path;
        }

        $component->update($componentData);

        // Clear cache
        Cache::tags(['components'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Component updated successfully',
            'data' => $component,
        ]);
    }

    /**
     * Delete component
     */
    public function destroy(Component $component)
    {
        // Delete file if exists
        if ($component->file_path && Storage::disk('public')->exists($component->file_path)) {
            Storage::disk('public')->delete($component->file_path);
        }

        $component->delete();

        // Clear cache
        Cache::tags(['components'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Component deleted successfully',
        ]);
    }

    /**
     * Bulk operations
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:components,id',
        ]);

        $components = Component::whereIn('id', $validated['ids']);

        switch ($validated['action']) {
            case 'delete':
                foreach ($components->get() as $component) {
                    if ($component->file_path && Storage::disk('public')->exists($component->file_path)) {
                        Storage::disk('public')->delete($component->file_path);
                    }
                }
                $components->delete();
                $message = 'Components deleted successfully';
                break;

            case 'activate':
                $components->update(['is_active' => true]);
                $message = 'Components activated successfully';
                break;

            case 'deactivate':
                $components->update(['is_active' => false]);
                $message = 'Components deactivated successfully';
                break;
        }

        // Clear cache
        Cache::tags(['components'])->flush();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Download component
     */
    public function download(Component $component, Request $request)
    {
        if ($component->type !== 'free') {
            // Check if user has purchased
            $hasAccess = $request->user() && $request->user()->purchases()
                ->where('component_id', $component->id)
                ->where('status', 'completed')
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this component',
                ], 403);
            }
        }

        if (!$component->file_path || !Storage::disk('public')->exists($component->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Component file not found',
            ], 404);
        }

        // Record download
        if ($request->user()) {
            $request->user()->downloads()->create([
                'component_id' => $component->id,
            ]);
        }

        return Storage::disk('public')->download($component->file_path);
    }

    /**
     * Get component statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Component::count(),
            'active' => Component::where('is_active', true)->count(),
            'inactive' => Component::where('is_active', false)->count(),
            'free' => Component::where('type', 'free')->count(),
            'paid' => Component::where('type', 'paid')->count(),
            'premium' => Component::where('type', 'premium')->count(),
            'by_category' => Component::select('category')
                ->selectRaw('count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
ENDOFFILE
echo "EnhancedComponentController created"
