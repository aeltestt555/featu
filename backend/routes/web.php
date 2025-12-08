<?php

use App\Http\Controllers\FacebookWebhookController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return 'welcome';
// });

Route::get('/', [LandingController::class, 'show'])->name('landing.show');
Route::post('/submit', [LandingController::class, 'submit'])->name('landing.submit');
Route::get('/thank-you', [LandingController::class, 'thankyou'])->name('landing.thankyou');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

// routes/web.php
Route::get('/webhook/facebook-leads', [FacebookWebhookController::class, 'verify']);
Route::post('/webhook/facebook-leads', [FacebookWebhookController::class, 'receive']);
