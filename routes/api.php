
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ComponentController;
use App\Http\Controllers\Api\V1\TemplateController;
use App\Http\Controllers\Api\V1\PurchaseController;
use App\Http\Controllers\Api\V1\AnalyticsController;

Route::prefix('v1')->group(function () {
    // Public API - rate limited
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/components', [ComponentController::class, 'index']);
        Route::get('/components/{slug}', [ComponentController::class, 'show']);
        Route::get('/templates', [TemplateController::class, 'index']);
        Route::get('/templates/{slug}', [TemplateController::class, 'show']);
    });

    // Protected download endpoint - stricter rate limiting
    Route::middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {
        Route::get('/components/{slug}/download', [ComponentController::class, 'download']);
    });

    // Purchase routes
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('/templates/{slug}/purchase', [TemplateController::class, 'purchase']);
        Route::post('/purchases', [PurchaseController::class, 'store']);
        Route::get('/purchases', [PurchaseController::class, 'index']);
        Route::get('/purchases/history', [PurchaseController::class, 'billingHistory']);
    });

    // Analytics routes
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/analytics/revenue', [AnalyticsController::class, 'revenue']);
        Route::get('/analytics/downloads', [AnalyticsController::class, 'downloads']);
        Route::get('/analytics/users', [AnalyticsController::class, 'users']);
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    });
});
