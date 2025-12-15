<?php

use App\Http\Controllers\FacebookWebhookController;
use App\Http\Controllers\LandingController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return 'welcome';
// });

Route::get('/lp', [LandingController::class, 'show'])->name('landing.show');
Route::post('/submit', [LandingController::class, 'submit'])->name('landing.submit');
Route::get('/thank-you', [LandingController::class, 'thankyou'])->name('landing.thankyou');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

