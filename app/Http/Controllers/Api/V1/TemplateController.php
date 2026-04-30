<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Services\ComponentAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    protected $accessService;

    public function __construct(ComponentAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    public function index(Request $request)
    {
        $query = Template::where('is_active', true);

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

        $templates = $query->orderBy('created_at', 'desc')->paginate(20);

        $user = $request->user();
        if ($user) {
            $templates->getCollection()->transform(function ($template) use ($user) {
                $template->accessible = $this->accessService->canAccessTemplate($user, $template);
                $template->purchased = $user->purchases()
                    ->where('purchasable_type', 'App\Models\Template')
                    ->where('purchasable_id', $template->id)
                    ->where('payment_status', 'completed')
                    ->exists();
                return $template;
            });
        } else {
            $templates->getCollection()->transform(function ($template) {
                $template->accessible = $template->type === 'free';
                $template->purchased = false;
                return $template;
            });
        }

        return response()->json(['success' => true, 'data' => $templates]);
    }

    public function show(Request $request, string $slug)
    {
        $template = Template::where('slug', $slug)->where('is_active', true)->first();
        if (!$template) {
            return response()->json(['success' => false, 'error' => 'Template not found'], 404);
        }

        $user = $request->user();
        if ($user) {
            $canAccess = $this->accessService->canAccessTemplate($user, $template);
            $html = $this->accessService->getTemplateHTML($user, $template);
        } else {
            $canAccess = $template->type === 'free';
            $html = $canAccess ? $template->preview_html : null;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
                'description' => $template->description,
                'type' => $template->type,
                'preview_html' => $html,
                'metadata' => $template->metadata,
                'download_count' => $template->download_count,
                'created_at' => $template->created_at,
                'accessible' => $canAccess
            ]
        ], $canAccess ? 200 : 403);
    }

    public function purchase(Request $request, string $slug)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Authentication required'], 401);
        }

        $template = Template::where('slug', $slug)->where('is_active', true)->first();
        if (!$template) {
            return response()->json(['success' => false, 'error' => 'Template not found'], 404);
        }

        if ($template->type === 'free') {
            return response()->json(['success' => false, 'error' => 'Template is already free'], 400);
        }

        $alreadyPurchased = $user->purchases()
            ->where('purchasable_type', 'App\Models\Template')
            ->where('purchasable_id', $template->id)
            ->where('payment_status', 'completed')
            ->exists();

        if ($alreadyPurchased) {
            return response()->json(['success' => true, 'already_purchased' => true, 'message' => 'Already owned']);
        }

        $amount = $template->metadata['price'] ?? 99.99;
        return response()->json(['success' => true, 'template' => $template, 'amount' => $amount, 'message' => 'Proceed to checkout']);
    }
}
