<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Services\ComponentAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComponentController extends Controller
{
    protected $accessService;

    public function __construct(ComponentAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * List all components
     */
    public function index(Request $request)
    {
        $query = Component::where('is_active', true);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $components = $query->orderBy('created_at', 'desc')->paginate(20);

        $user = $request->user();
        if ($user) {
            $components->getCollection()->transform(function ($component) use ($user) {
                $component->accessible = $this->accessService->canAccessComponent($user, $component);
                $component->purchased = $user->purchases()
                    ->where('purchasable_type', 'App\Models\Component')
                    ->where('purchasable_id', $component->id)
                    ->where('payment_status', 'completed')
                    ->exists();
                return $component;
            });
        } else {
            $components->getCollection()->transform(function ($component) {
                $component->accessible = $component->type === 'free';
                $component->purchased = false;
                return $component;
            });
        }

        return response()->json(['success' => true, 'data' => $components]);
    }

    /**
     * Get specific component
     */
    public function show(Request $request, string $slug)
    {
        $component = Component::where('slug', $slug)->where('is_active', true)->first();

        if (!$component) {
            return response()->json(['success' => false, 'error' => 'Component not found'], 404);
        }

        $user = $request->user();
        if ($user) {
            $canAccess = $this->accessService->canAccessComponent($user, $component);
            $code = $this->accessService->getComponentCode($user, $component);
        } else {
            $canAccess = $component->type === 'free';
            $code = $canAccess ? $component->code_snippet : null;
        }

        $response = [
            'success' => true,
            'data' => [
                'id' => $component->id,
                'name' => $component->name,
                'slug' => $component->slug,
                'description' => $component->description,
                'category' => $component->category,
                'type' => $component->type,
                'preview_html' => $component->preview_html,
                'metadata' => $component->metadata,
                'created_at' => $component->created_at,
                'accessible' => $canAccess,
                'code_snippet' => $canAccess ? $code : null
            ]
        ];

        if (!$canAccess) {
            $response['data']['message'] = 'Premium component. Upgrade subscription or purchase to access.';
        }

        return response()->json($response, $canAccess ? 200 : 403);
    }

    /**
     * Download component for CLI
     */
    public function download(Request $request, string $slug)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Authentication required'], 401);
        }

        $component = Component::where('slug', $slug)->where('is_active', true)->first();
        if (!$component) {
            return response()->json(['success' => false, 'error' => 'Component not found'], 404);
        }

        $result = $this->accessService->downloadComponentForCLI($user, $component);
        if ($result['success']) {
            $component->increment('download_count');
        }

        return response()->json($result, $result['code'] ?? 200);
    }
}
