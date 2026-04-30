<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Component;
use App\Models\Template;
use App\Models\Purchase;
use App\Models\User;
use App\Services\ComponentAccessService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $user = Auth::user();
        $purchases = Purchase::with('template')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.profile', compact('user', 'purchases'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function myComponents()
    {
        $purchases = Purchase::with('template')
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.my-components', compact('purchases'));
    }

    public function downloadComponent($id)
    {
        $purchase = Purchase::where('user_id', Auth::id())
            ->where('component_id', $id)
            ->where('status', 'completed')
            ->firstOrFail();

        $component = $purchase->component;

        // Log download
        $component->increment('download_count');

        app(ComponentAccessService::class)->recordDownload($component, Auth::user());

        $filePath = storage_path('app/components/' . $component->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, $component->file_name);
        }

        return back()->with('error', 'File not found.');
    }

    public function downloadTemplate($id)
    {
        $purchase = Purchase::where('user_id', Auth::id())
            ->where('template_id', $id)
            ->where('status', 'completed')
            ->firstOrFail();

        $template = $purchase->template;

        // Log download
        $template->increment('download_count');

        $filePath = storage_path('app/templates/' . $template->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, $template->file_name);
        }

        return back()->with('error', 'File not found.');
    }

    public function wishlist()
    {
        $user = Auth::user();
        $wishlistedComponents = $user->wishlistedComponents()->get();
        $wishlistedTemplates = $user->wishlistedTemplates()->get();

        return view('user.wishlist', compact('wishlistedComponents', 'wishlistedTemplates'));
    }

    public function toggleWishlistComponent(Request $request, Component $component)
    {
        $user = Auth::user();

        if ($user->wishlistedComponents()->where('component_id', $component->id)->exists()) {
            $user->wishlistedComponents()->detach($component->id);
            $action = 'removed';
        } else {
            $user->wishlistedComponents()->attach($component->id);
            $action = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['action' => $action]);
        }

        return back()->with('success', 'Wishlist updated.');
    }

    public function toggleWishlistTemplate(Request $request, Template $template)
    {
        $user = Auth::user();

        if ($user->wishlistedTemplates()->where('template_id', $template->id)->exists()) {
            $user->wishlistedTemplates()->detach($template->id);
            $action = 'removed';
        } else {
            $user->wishlistedTemplates()->attach($template->id);
            $action = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['action' => $action]);
        }

        return back()->with('success', 'Wishlist updated.');
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return back();
    }

    public function billing()
    {
        $user = Auth::user();
        $purchases = Purchase::with('template')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'refunded'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.billing', compact('purchases'));
    }

    public function security()
    {
        return view('user.security');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function myDesigns()
    {
        $user = Auth::user();
        $components = Component::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $templates = Template::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.my-designs', compact('components', 'templates'));
    }

    public function submitComponent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'file' => 'required|file|mimes:zip|max:51200',
        ]);

        // Store file and create component pending approval
        $user = Auth::user();
        $component = Component::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'user_id' => $user->id,
            'is_active' => false,
            'status' => 'pending',
        ]);

        return redirect()->route('user.my-designs')
            ->with('success', 'Component submitted for approval.');
    }
}
