
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\StripeWebhookController;

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
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook'])->name('webhook.stripe');

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

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.home');
    
    Route::get('/components', [AdminController::class, 'components'])->name('admin.components');
    Route::get('/components/create', [AdminController::class, 'createComponent'])->name('admin.components.create');
    Route::post('/components', [AdminController::class, 'storeComponent'])->name('admin.components.store');
    Route::get('/components/{component}/edit', [AdminController::class, 'editComponent'])->name('admin.components.edit');
    Route::put('/components/{component}', [AdminController::class, 'updateComponent'])->name('admin.components.update');
    Route::delete('/components/{component}', [AdminController::class, 'deleteComponent'])->name('admin.components.destroy');
    
    Route::get('/templates', [AdminController::class, 'templates'])->name('admin.templates');
    Route::get('/templates/create', [AdminController::class, 'createTemplate'])->name('admin.templates.create');
    Route::post('/templates', [AdminController::class, 'storeTemplate'])->name('admin.templates.store');
    Route::get('/templates/{template}/edit', [AdminController::class, 'editTemplate'])->name('admin.templates.edit');
    Route::put('/templates/{template}', [AdminController::class, 'updateTemplate'])->name('admin.templates.update');
    Route::delete('/templates/{template}', [AdminController::class, 'deleteTemplate'])->name('admin.templates.destroy');
    
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    
    Route::get('/purchases', [AdminController::class, 'purchases'])->name('admin.purchases');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

// User Routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/my-components', [UserController::class, 'myComponents'])->name('user.components');
    Route::get('/download/component/{id}', [UserController::class, 'downloadComponent'])->name('user.components.download');
    Route::get('/download/template/{id}', [UserController::class, 'downloadTemplate'])->name('user.templates.download');
    Route::get('/wishlist', [UserController::class, 'wishlist'])->name('user.wishlist');
    Route::post('/wishlist/component/{component}', [UserController::class, 'toggleWishlistComponent'])->name('user.wishlist.component');
    Route::post('/wishlist/template/{template}', [UserController::class, 'toggleWishlistTemplate'])->name('user.wishlist.template');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('user.notifications');
    Route::get('/notifications/{id}/read', [UserController::class, 'markNotificationAsRead'])->name('user.notifications.read');
    Route::get('/billing', [UserController::class, 'billing'])->name('user.billing');
    Route::get('/security', [UserController::class, 'security'])->name('user.security');
    Route::put('/security/password', [UserController::class, 'updatePassword'])->name('user.security.password');
    Route::get('/my-designs', [UserController::class, 'myDesigns'])->name('user.designs');
    Route::post('/designs/component', [UserController::class, 'submitComponent'])->name('user.designs.component');
});

// Chat UI Route
Route::get('/chatui', [ChatUIController::class, 'index'])->name('chatui.index');
Route::post('/chatui/send', [ChatUIController::class, 'sendMessage'])->name('chatui.send');
Route::post('/chatui/suggestion/{id}', [ChatUIController::class, 'selectSuggestion'])->name('chatui.suggestion');
Route::post('/chatui/recording/start', [ChatUIController::class, 'toggleRecording'])->name('chatui.recording.start');
Route::post('/chatui/recording/stop', [ChatUIController::class, 'toggleRecording'])->name('chatui.recording.stop');
Route::get('/chatui/history', [ChatUIController::class, 'getHistory'])->name('chatui.history');
Route::post('/chatui/search', [ChatUIController::class, 'webSearch'])->name('chatui.search');
Route::post('/chatui/upload', [ChatUIController::class, 'uploadImage'])->name('chatui.upload');
Route::get('/chatui/templates', [ChatUIController::class, 'getTemplates'])->name('chatui.templates');
Route::get('/chatui/color-palettes', [ChatUIController::class, 'getColorPalettes'])->name('chatui.color-palettes');

// Prompt Gallery Routes
Route::get('/prompt-gallery', [PromptGalleryController::class, 'index'])->name('prompt-gallery.index');
Route::get('/prompt-gallery/{id}', [PromptGalleryController::class, 'show'])->name('prompt-gallery.show');
Route::post('/prompt-gallery/{id}/copy', [PromptGalleryController::class, 'copy'])->name('prompt-gallery.copy');
