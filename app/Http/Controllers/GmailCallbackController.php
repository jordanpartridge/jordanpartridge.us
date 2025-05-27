<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GmailCallbackController extends Controller
{
    /**
     * Handle the callback from Google OAuth
     */
    public function __invoke(Request $request)
    {
        Log::info('Gmail callback reached', [
            'has_code'           => $request->has('code'),
            'user_id'            => auth()->id(),
            'user_authenticated' => auth()->check(),
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
                // Manual token exchange using Laravel's HTTP client instead of package
                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => config('gmail-client.client_id'),
                    'client_secret' => config('gmail-client.client_secret'),
                    'redirect_uri'  => config('gmail-client.redirect_uri'),
                    'code'          => $code,
                ]);

                if ($response->failed()) {
                    throw new Exception('Token exchange failed: ' . $response->body());
                }

                $tokens = $response->json();

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
            $expiresIn = $tokens['expires_in'] ?? 3600; // default 1 hour
            $expiresAt = now()->addSeconds($expiresIn);

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
                // First, get the user's Gmail profile to extract email address
                $profileResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $tokens['access_token'],
                ])->get('https://www.googleapis.com/oauth2/v2/userinfo');

                $profile = $profileResponse->json();
                $gmailEmail = $profile['email'] ?? null;
                $profileName = $profile['name'] ?? null;

                Log::info('Retrieved Gmail profile', [
                    'email' => $gmailEmail,
                    'name'  => $profileName,
                ]);

                // Check if this Gmail account is already connected
                $existingAccount = auth()->user()->gmailAccounts()
                    ->where('gmail_email', $gmailEmail)
                    ->first();

                if ($existingAccount) {
                    // Update existing account
                    $existingAccount->update([
                        'access_token'  => $tokens['access_token'],
                        'refresh_token' => $tokens['refresh_token'] ?? $existingAccount->refresh_token,
                        'expires_at'    => $expiresAt,
                        'last_sync_at'  => now(),
                        'account_info'  => $profile,
                    ]);

                    $token = $existingAccount;
                    $message = "Gmail account '{$gmailEmail}' has been reconnected successfully!";
                } else {
                    // Create new Gmail account
                    $isPrimary = auth()->user()->gmailAccounts()->count() === 0; // First account becomes primary

                    $token = auth()->user()->gmailAccounts()->create([
                        'gmail_email'   => $gmailEmail,
                        'account_name'  => $profileName ?: $gmailEmail,
                        'is_primary'    => $isPrimary,
                        'access_token'  => $tokens['access_token'],
                        'refresh_token' => $tokens['refresh_token'] ?? null,
                        'expires_at'    => $expiresAt,
                        'last_sync_at'  => now(),
                        'account_info'  => $profile,
                    ]);

                    $message = "Gmail account '{$gmailEmail}' has been connected successfully!" .
                              ($isPrimary ? ' This is now your primary account.' : '');
                }

                // Debug log
                Log::info('Gmail OAuth tokens stored in database', [
                    'token_id'          => $token->id,
                    'user_id'           => auth()->id(),
                    'gmail_email'       => $gmailEmail,
                    'is_primary'        => $token->is_primary,
                    'token_expires_at'  => $expiresAt->toDateTimeString(),
                    'has_refresh_token' => isset($tokens['refresh_token']),
                    'is_new_account'    => !$existingAccount,
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
