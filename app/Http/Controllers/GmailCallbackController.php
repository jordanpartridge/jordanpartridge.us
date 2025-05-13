<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PartridgeRocks\GmailClient\Facades\GmailClient;

class GmailCallbackController extends Controller
{
    /**
     * Handle the callback from Google OAuth
     */
    public function __invoke(Request $request)
    {
        Log::info('Gmail callback reached with improved logging', [
            'has_code'           => $request->has('code'),
            'user_id'            => auth()->id(),
            'user_authenticated' => auth()->check(),
            'callback_uri'       => config('gmail-client.redirect_uri'),
            'request_uri'        => $request->getRequestUri(),
            'request_query'      => $request->getQueryString(),
        ]);

        $code = $request->get('code');

        if (empty($code)) {
            return redirect()->route('filament.admin.pages.gmail-integration-page')
                ->with('error', 'Authorization code is missing.');
        }

        try {
            Log::info('Attempting to exchange code for tokens');

            // Debug the config
            Log::info('Gmail config check', [
                'client_id'    => config('gmail-client.client_id'),
                'redirect_uri' => config('gmail-client.redirect_uri'),
                'from_email'   => config('gmail-client.from_email'),
                'scopes'       => config('gmail-client.scopes'),
            ]);

            try {
                // Exchange code for tokens
                $tokens = GmailClient::exchangeCode(
                    $code,
                    config('gmail-client.redirect_uri')
                );

                Log::info('Successfully exchanged code for tokens', [
                    'has_access_token'  => isset($tokens['access_token']),
                    'has_refresh_token' => isset($tokens['refresh_token']),
                    'expires_in'        => $tokens['expires_in'] ?? 'unknown',
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to exchange code for tokens', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                throw $e;
            }

            // Store the token in the database for the authenticated user
            $expiresAt = now()->addSeconds($tokens['expires_in']);

            // Check if there's an authenticated user
            if (!auth()->check()) {
                Log::error('No authenticated user when trying to store Gmail token');

                // Since we're coming from Google's OAuth flow, we need to redirect to login
                // but preserve the OAuth tokens in session for later
                session([
                    'gmail_temp_access_token'  => $tokens['access_token'],
                    'gmail_temp_refresh_token' => $tokens['refresh_token'] ?? null,
                    'gmail_temp_expires_at'    => $expiresAt->toDateTimeString(),
                ]);

                return redirect()->route('login')
                    ->with('message', 'Please log in to complete Gmail authentication');
            }

            try {
                // Update or create the Gmail token for the user
                $token = auth()->user()->gmailToken()->updateOrCreate(
                    [], // Empty array means we'll update the token if it exists
                    [
                        'access_token'  => $tokens['access_token'],
                        'refresh_token' => $tokens['refresh_token'] ?? null,
                        'expires_at'    => $expiresAt,
                    ]
                );

                // Debug log
                Log::info('Gmail OAuth tokens stored in database', [
                    'token_id'          => $token->id,
                    'user_id'           => auth()->id(),
                    'token_expires_at'  => $expiresAt->toDateTimeString(),
                    'has_refresh_token' => isset($tokens['refresh_token']),
                    'token_saved'       => $token->exists,
                ]);

                // Double-check if token was actually saved
                $savedToken = auth()->user()->gmailToken()->first();
                Log::info('Saved token verification', [
                    'token_exists'        => $savedToken ? 'Yes' : 'No',
                    'token_id'            => $savedToken ? $savedToken->id : null,
                    'access_token_length' => $savedToken ? mb_strlen($savedToken->access_token) : 0,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to store Gmail token in database', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->route('filament.admin.pages.gmail-integration-page')
                    ->with('error', 'Failed to store Gmail token: ' . $e->getMessage());
            }

            return redirect()->route('filament.admin.pages.gmail-integration-page')
                ->with('success', 'Successfully authenticated with Gmail!');
        } catch (\Exception $e) {
            return redirect()->route('filament.admin.pages.gmail-integration-page')
                ->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
