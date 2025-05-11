<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClientExportController;
use App\Http\Middleware\LogRequests;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('web')->group(function () {
    Route::view('/', 'index')->name('welcome');

    Route::view('privacy-policy', 'privacy-policy')->name('privacy-policy');

    Route::view('cookies', 'cookies')->name('cookies');

    Route::view('about', 'about')->name('about');

    Route::middleware('guest')->group(function () {
        Route::view('login', 'auth.login')->name('login');

        Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

        Route::view('email/verify', 'auth.verify-email')->name('verification.notice');
    });

    Route::middleware('auth')->group(function () {
        Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)->name('verification.verify');

        Route::post('logout', LogoutController::class)->name('logout');

        Route::view('training', 'training')->name('training');

        Route::view('resources', 'resources')->name('resources');

        Route::view('contact', 'contact')->name('contact');

        Route::view('thanks', 'thanks')->name('thanks');
    });

    Route::get('health', fn () => 'ok')->name('health');

    Route::post('webhooks/github', [WebhookController::class, 'github'])
        ->middleware([LogRequests::class, VerifyCsrfToken::class])
        ->name('webhooks.github');

    Route::post('webhooks/ably', [WebhookController::class, 'ably'])
        ->middleware([LogRequests::class, VerifyCsrfToken::class])
        ->name('webhooks.ably');

    Route::view('subscribe', 'livewire.subscribe')->name('subscribe');

    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

    // Client export
    Route::get('clients/export', ClientExportController::class)
        ->middleware(['auth'])
        ->name('clients.export');

});
