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
| Routes are organized into logical groups:
| 1. Public Redirects
| 2. Webhook Endpoints
| 3. Authentication & Verification
| 4. Client Management
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

        /*
        |--------------------------------------------------------------------------
        | Client Management Routes
        |--------------------------------------------------------------------------
        */
        // Client export
        Route::get('clients/export', ClientExportController::class)
            ->name('clients.export');

        // Client document download
        Route::get('client-documents/{document}/download', function (App\Models\ClientDocument $document) {
            if (auth()->user()->cannot('view', $document->client)) {
                abort(403);
            }
            return redirect()->away($document->signed_url);
        })->name('client-documents.download');

        // Log client contact
        Route::post('clients/{client}/log-contact', function (App\Models\Client $client) {
            if (auth()->user()->cannot('update', $client)) {
                abort(403);
            }
            $client->update(['last_contact_at' => now()]);
            return redirect()->back()->with('status', 'Contact logged successfully.');
        })->name('clients.log-contact');
    });

    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
});
