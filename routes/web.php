<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Cards\DeckInitializeController;
use App\Http\Controllers\Strava\CallbackController;
use App\Http\Controllers\Strava\RedirectController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\LogRequests;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware([LogRequests::class])->group(function () {
    Route::get('cards/{deckName}/initialize', DeckInitializeController::class)->name('cards:initialize');
    Route::redirect('home', '/')->name('home');

    Route::post('slack', WebhookController::class)->name('web:hook')->withoutMiddleware(VerifyCsrfToken::class);

    Route::middleware('auth')->group(callback: function () {
        Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
            ->middleware('signed')
            ->name('verification:verify');
        Route::post('logout', LogoutController::class)
            ->name('logout');

        Route::prefix('strava')->as('strava:')->group(function () {
            Route::get('redirect', RedirectController::class)->name('redirect');
            Route::get('callback', CallbackController::class)->name('callback');
        });

        Route::prefix('cards')->as('cards:')->group(function () {
            Route::get('initialize', DeckInitializeController::class)
                ->name('initialize');
        });
    });
});

// Include terminal routes
require __DIR__.'/terminal.php';
