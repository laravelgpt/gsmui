
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\RegisteredUserController;

// Home & Marketing
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/components', [WebController::class, 'components'])->name('components');
Route::get('/templates', [WebController::class, 'templates'])->name('templates');
Route::get('/docs', [WebController::class, 'docs'])->name('docs');
Route::get('/dashboard', [WebController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Auth Routes
Route::get('/login', [WebController::class, 'login'])->name('login');
Route::post('/login', [RegisteredUserController::class, 'store']);
Route::get('/register', [WebController::class, 'register'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Stripe Webhook
Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('webhook.stripe');

// Admin Routes (Filament)
Route::get('/admin', function () {
    return redirect()->route('filament.admin.dashboard');
})->middleware('auth')->name('admin');

// Theme Settings
Route::get('/admin/theme', function () {
    return view('filament.pages.theme-settings');
})->middleware('auth')->name('admin.theme');

// Template Preview Routes
Route::prefix('templates')->group(function () {
    Route::get('/gsm-flasher', function () {
        return view('admin.templates.gsm-flasher');
    })->name('templates.gsm-flasher');
    
    Route::get('/forensic-viewer', function () {
        return view('admin.templates.forensic-viewer');
    })->name('templates.forensic-viewer');
    
    Route::get('/server-monitor', function () {
        return view('admin.templates.server-monitor');
    })->name('templates.server-monitor');
    
    Route::get('/network-scanner', function () {
        return view('admin.templates.network-scanner');
    })->name('templates.network-scanner');
    
    Route::get('/evidence-management', function () {
        return view('admin.templates.evidence-management');
    })->name('templates.evidence-management');
    
    Route::get('/signal-analyzer', function () {
        return view('admin.templates.signal-analyzer');
    })->name('templates.signal-analyzer');
    
    Route::get('/incident-response', function () {
        return view('admin.templates.incident-response');
    })->name('templates.incident-response');
    
    Route::get('/data-breach', function () {
        return view('admin.templates.data-breach');
    })->name('templates.data-breach');
    
    Route::get('/mobile-forensics', function () {
        return view('admin.templates.mobile-forensics');
    })->name('templates.mobile-forensics');
    
    Route::get('/soc-dashboard', function () {
        return view('admin.templates.soc-dashboard');
    })->name('templates.soc-dashboard');
});
Route::get("/playground", function () { return view("playground"); });
