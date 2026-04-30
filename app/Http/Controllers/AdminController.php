<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;
use App\Models\Template;
use App\Models\User;
use App\Models\Purchase;
use App\Services\ComponentAccessService;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_components' => Component::count(),
            'active_components' => Component::where('is_active', true)->count(),
            'total_templates' => Template::count(),
            'active_templates' => Template::where('is_active', true)->count(),
            'total_users' => User::count(),
            'total_purchases' => Purchase::count(),
            'total_revenue' => Purchase::where('status', 'completed')->sum('amount'),
        ];

        $recentPurchases = Purchase::with('user', 'template')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentComponents = Component::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPurchases', 'recentComponents'));
    }

    public function components()
    {
        $components = Component::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.components.index', compact('components'));
    }

    public function createComponent()
    {
        return view('admin.components.create');
    }

    public function storeComponent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'preview_url' => 'nullable|url',
        ]);

        $component = Component::create($validated);

        return redirect()->route('admin.components.edit', $component->id)
            ->with('success', 'Component created successfully.');
    }

    public function editComponent(Component $component)
    {
        return view('admin.components.edit', compact('component'));
    }

    public function updateComponent(Request $request, Component $component)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'preview_url' => 'nullable|url',
        ]);

        $component->update($validated);

        return back()->with('success', 'Component updated successfully.');
    }

    public function deleteComponent(Component $component)
    {
        $component->delete();
        return redirect()->route('admin.components')->with('success', 'Component deleted successfully.');
    }

    public function templates()
    {
        $templates = Template::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.templates.index', compact('templates'));
    }

    public function createTemplate()
    {
        return view('admin.templates.create');
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'preview_url' => 'nullable|url',
        ]);

        $template = Template::create($validated);

        return redirect()->route('admin.templates.edit', $template->id)
            ->with('success', 'Template created successfully.');
    }

    public function editTemplate(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function updateTemplate(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'preview_url' => 'nullable|url',
        ]);

        $template->update($validated);

        return back()->with('success', 'Template updated successfully.');
    }

    public function deleteTemplate(Template $template)
    {
        $template->delete();
        return redirect()->route('admin.templates')->with('success', 'Template deleted successfully.');
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function purchases()
    {
        $purchases = Purchase::with(['user', 'template'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.purchases.index', compact('purchases'));
    }

    public function analytics()
    {
        $revenue = Purchase::where('status', 'completed')->sum('amount');
        $totalPurchases = Purchase::where('status', 'completed')->count();
        $totalUsers = User::count();
        $totalComponents = Component::count();
        $totalTemplates = Template::count();

        $monthlyRevenue = Purchase::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $monthlyPurchases = Purchase::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('admin.analytics', compact(
            'revenue',
            'totalPurchases',
            'totalUsers',
            'totalComponents',
            'totalTemplates',
            'monthlyRevenue',
            'monthlyPurchases'
        ));
    }

    public function settings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
