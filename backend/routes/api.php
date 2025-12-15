<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FacebookWebhookController;
use App\Http\Middleware\VerifyCsrfToken;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('jwt.verify')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // User management routes
    Route::apiResource('users', UserController::class);
});

// Admin only routes
Route::middleware(['jwt.verify', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});
Route::get('/', function () {
   return  'welcome';
});

// Route::get('/webhook/facebook-leads', [FacebookWebhookController::class, 'verify']);
// Route::post('/webhook/facebook-leads', [FacebookWebhookController::class, 'receive']);

// Facebook Webhook (CSRF Disabled)
Route::withoutMiddleware([VerifyCsrfToken::class])
    ->group(function () {
        Route::get('/webhook/facebook-leads', [FacebookWebhookController::class, 'verify']);
        Route::post('/webhook/facebook-leads', [FacebookWebhookController::class, 'receive']);
    });

    Route::post('/test-csrf', function () {
        return 'CSRF Passed!';
    });