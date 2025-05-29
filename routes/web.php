<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ClientExportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\LogRequests;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Cache;
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

/*
|--------------------------------------------------------------------------
| Gmail Integration Routes - Custom controller for database storage
|--------------------------------------------------------------------------
*/
// Gmail OAuth routes using custom controller that stores tokens in database
Route::group(['middleware' => ['web']], function () {
    // OAuth redirect to Google (uses package client)
    Route::get('gmail/auth/redirect', function () {
        $client = app(\PartridgeRocks\GmailClient\GmailClient::class);
        $authUrl = $client->getAuthorizationUrl(
            config('gmail-client.redirect_uri'),
            config('gmail-client.scopes')
        );

        return redirect($authUrl);
    })->name('gmail.auth.redirect');

    // OAuth callback that stores tokens in database
    Route::get('gmail/auth/callback', App\Http\Controllers\GmailCallbackController::class)
        ->name('gmail.auth.callback');

    // Error route for OAuth failures
    Route::get('gmail/error', function () {
        return redirect()->route('filament.admin.pages.gmail-integration-page')
            ->with('error', 'Gmail authentication failed. Please try again.');
    })->name('gmail.error');

});

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
        // Gmail debug route
        Route::get('debug/gmail', function () {
            try {
                $user = User::first();
                if (!$user) {
                    return response()->json(['error' => 'No users found']);
                }

                $result = [
                    'user_email'      => $user->email,
                    'has_valid_token' => $user->hasValidGmailToken(),
                ];

                if ($user->hasValidGmailToken()) {
                    $client = $user->getGmailClient();
                    if ($client) {
                        $result['client_obtained'] = true;
                        // Try a simple API call
                        $messages = $client->listMessages(['maxResults' => 3]);
                        $result['api_test'] = [
                            'messages_count'     => is_countable($messages) ? count($messages) : 'not countable',
                            'messages_type'      => gettype($messages),
                            'first_message_type' => isset($messages[0]) ? gettype($messages[0]) : 'none'
                        ];
                    } else {
                        $result['client_obtained'] = false;
                    }
                }

                return response()->json($result);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
        });
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

    /*
    |--------------------------------------------------------------------------
    | Gmail Integration Routes
    |--------------------------------------------------------------------------
    */
    // Gmail OAuth routes are automatically registered by the gmail-client package

    // RSS Feed route
    Route::get('feed.xml', function () {
        $content = Cache::remember('rss-feed', 60 * 15, function () {
            $posts = Post::where('status', 'published')
                       ->orderBy('created_at', 'desc')
                       ->limit(20)
                       ->get();

            return view('pages.feed.xml', ['posts' => $posts])->render();
        });

        return response($content)
            ->header('Content-Type', 'application/xml');
    })->name('feed.xml');

    // Sitemap route
    Route::get('sitemap.xml', function () {
        try {
            $content = Cache::remember('sitemap-xml', 60 * 60, function () {
                $posts = Post::where('status', 'published')
                    ->orderBy('updated_at', 'desc')
                    ->limit(1000)
                    ->get();
                $categories = Category::orderBy('name')
                    ->limit(100)
                    ->get();
                $baseUrl = config('app.url');
                $now = now()->toIso8601String();

                $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

                // Core pages
                $xml .= '<url><loc>' . e($baseUrl) . '</loc><lastmod>' . $now . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
                $xml .= '<url><loc>' . e($baseUrl) . '/services</loc><lastmod>' . $now . '</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>' . "\n";
                $xml .= '<url><loc>' . e($baseUrl) . '/work-with-me</loc><lastmod>' . $now . '</lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>' . "\n";
                $xml .= '<url><loc>' . e($baseUrl) . '/contact</loc><lastmod>' . $now . '</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>' . "\n";
                $xml .= '<url><loc>' . e($baseUrl) . '/blog</loc><lastmod>' . $now . '</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>' . "\n";

                // Categories
                foreach ($categories as $category) {
                    $lastmod = $category->updated_at ? $category->updated_at->toIso8601String() : $now;
                    $xml .= '<url><loc>' . e($baseUrl) . '/categories/' . e($category->slug) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>' . "\n";
                }

                // Posts
                foreach ($posts as $index => $post) {
                    $lastmod = $post->updated_at ? $post->updated_at->toIso8601String() : $now;
                    $priority = $index < 5 ? '0.7' : '0.6';
                    $changefreq = ($post->updated_at && $post->updated_at->diffInDays() < 30) ? 'weekly' : 'monthly';
                    $xml .= '<url><loc>' . e($baseUrl) . '/blog/' . e($post->slug) . '</loc><lastmod>' . $lastmod . '</lastmod><changefreq>' . $changefreq . '</changefreq><priority>' . $priority . '</priority></url>' . "\n";
                }

                $xml .= '</urlset>';
                return $xml;
            });

            return response($content)
                ->header('Content-Type', 'application/xml; charset=UTF-8');
        } catch (\Exception $e) {
            Log::error('Sitemap generation failed', ['error' => $e->getMessage()]);
            return response(
                '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>',
                500
            )->header('Content-Type', 'application/xml; charset=UTF-8');
        }
    })->name('sitemap.xml');
});
