<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Cards\DeckInitializeController;
use App\Http\Controllers\WebhookController;
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
    | Webhook Endpoints
    |--------------------------------------------------------------------------
    */
    Route::post('slack', WebhookController::class)
        ->name('web:hook')
        ->withoutMiddleware(VerifyCsrfToken::class);

    /*
    |--------------------------------------------------------------------------
    | Card Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('cards')->as('cards:')->group(function () {
        // Public card routes
        Route::get('{deckName}/initialize', DeckInitializeController::class)
            ->name('initialize');

        // Authenticated card routes
        Route::middleware('auth')->group(function () {
            Route::get('initialize', DeckInitializeController::class)
                ->name('initialize');
        });
    });

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
});
