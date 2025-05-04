<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\LogRequests;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes are organized into logical groups:
| 1. Public Redirects
| 2. Webhook Endpoints
| 3. Card Management
| 4. Authentication & Verification
|
*/

Route::middleware([LogRequests::class])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Public Redirects
    |--------------------------------------------------------------------------
    */
    Route::redirect('login', '/admin/login')->name('login');
    Route::redirect('home', '/')->name('home');

    /*
    |--------------------------------------------------------------------------
    | Test Routes (for development only)
    |--------------------------------------------------------------------------
    */
    if (app()->environment('local')) {
        Route::get('test-linkedin-meta', function () {
            return view('tests.linkedin-meta-test');
        })->name('test.linkedin-meta');
    }

    /*
    |--------------------------------------------------------------------------
    | Webhook Endpoints
    |--------------------------------------------------------------------------
    */
    Route::post('slack', WebhookController::class)
        ->name('web:hook')
        ->withoutMiddleware(VerifyCsrfToken::class);
    /*
    |--------------------------------------------------------------------------
    | Authentication & Verification Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        // Email verification
        Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
            ->middleware('signed')
            ->name('verification:verify');

        // Logout
        Route::post('logout', LogoutController::class)
            ->name('logout');
    });


    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

});
