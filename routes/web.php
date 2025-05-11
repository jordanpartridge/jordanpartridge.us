<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ClientExportController;
use App\Http\Controllers\ContactController;
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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::view('register', 'auth.register')->name('register');
    Route::view('login', 'auth.login')->name('login');
    Route::view('forgot-password', 'auth.forgot-password')->name('password.request');
    Route::view('reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationController::class, 'create'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('logout', [LogoutController::class, 'store'])->name('logout');
});

Route::post('webhooks/stripe', fn () => response('ok'))->name('cashier.webhook');

Route::get('resume', fn () => view('resume'))->name('resume');

Route::get('about', fn () => view('about'))->name('about');

Route::get('dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::post('stripe/webhook', fn () => response('Webhook received successfully.', 200))->name('stripe.webhook');

Route::withoutMiddleware([LogRequests::class, VerifyCsrfToken::class])->group(function () {
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

    // Client document download
    Route::get('client-documents/{document}/download', function (App\Models\ClientDocument $document) {
        if (!auth()->check() || auth()->user()->cannot('view', $document->client)) {
            abort(403);
        }
        return redirect()->away($document->signed_url);
    })->middleware(['auth'])->name('client-documents.download');

});
