<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ClientExportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\LogRequests;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\Post;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
        Route::get('client-documents/{document}/download', function (ClientDocument $document) {
            if (auth()->user()->cannot('view', $document->client)) {
                abort(403);
            }

            // Log the document download activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($document->client)
                ->withProperties([
                    'document_id' => $document->id,
                    'filename'    => $document->original_filename,
                ])
                ->log('downloaded_document');

            return redirect()->away($document->signed_url);
        })->name('client-documents.download');

        // Client document delete
        Route::delete('client-documents/{document}', function (ClientDocument $document) {
            // Allow only the uploader or administrators to delete
            if (auth()->id() !== $document->uploaded_by && !auth()->user()->hasRole('admin')) {
                abort(403);
            }

            // Store document info for activity log
            $documentInfo = [
                'document_id' => $document->id,
                'filename'    => $document->original_filename,
                'client_id'   => $document->client_id,
            ];

            try {
                // Delete the file from storage
                Storage::disk('s3')->delete($document->filename);

                // Delete the database record
                $document->delete();

                // Log the deletion
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($document->client)
                    ->withProperties($documentInfo)
                    ->log('deleted_document');

                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully',
                ]);
            } catch (\Exception $e) {
                // Log error
                Log::error('Document deletion failed', [
                    'document'  => $documentInfo,
                    'exception' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete document',
                ], 500);
            }
        })->name('client-documents.delete');

        // Log client contact
        Route::post('clients/{client}/log-contact', function (Client $client) {
            if (auth()->user()->cannot('update', $client)) {
                abort(403);
            }
            $client->update(['last_contact_at' => now()]);
            return redirect()->back()->with('status', 'Contact logged successfully.');
        })->name('clients.log-contact');
    });

    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

    // RSS Feed route
    Route::get('feed.xml', function () {
        $posts = Post::where('status', 'published')
                   ->orderBy('created_at', 'desc')
                   ->limit(20)
                   ->get();

        return response()
            ->view('pages.feed.xml', compact('posts'))
            ->header('Content-Type', 'application/xml');
    });
});
