
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;
use App\Models\Template;
use App\Models\Setting;
use App\Services\ComponentAccessService;

class WebController extends Controller
{
    public function index()
    {
        $components = Component::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $templates = Template::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('index', compact('components', 'templates'));
    }

    public function components()
    {
        return view('user.components');
    }

    public function templates()
    {
        return view('user.templates');
    }

    public function docs()
    {
        return view('user.docs');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function register()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }
}
