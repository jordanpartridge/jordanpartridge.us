<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPendingGmailAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we have temporary Gmail tokens stored in session from pre-login OAuth
        if (auth()->check() &&
            session()->has('gmail_temp_access_token') &&
            session()->has('gmail_temp_expires_at')) {

            $user = auth()->user();

            try {
                // Save the tokens to the database
                $user->gmailToken()->updateOrCreate(
                    ['user_id' => $user->id], // Explicitly match by user_id for safety
                    [
                        'access_token'  => session('gmail_temp_access_token'),
                        'refresh_token' => session('gmail_temp_refresh_token'),
                        'expires_at'    => session('gmail_temp_expires_at'),
                    ]
                );

                // Clear temporary tokens
                session()->forget([
                    'gmail_temp_access_token',
                    'gmail_temp_refresh_token',
                    'gmail_temp_expires_at'
                ]);

                // Log success
                Log::info('Stored postponed Gmail tokens for user', [
                    'user_id' => $user->id,
                ]);

                // Set a flash message about successful authentication
                session()->flash('success', 'Gmail authentication completed successfully!');

                // Redirect to Gmail Integration page instead of wherever they were going
                return redirect()->route('filament.admin.pages.gmail-integration-page');
            } catch (\Exception $e) {
                Log::error('Failed to store pending Gmail tokens', [
                    'error'   => $e->getMessage(),
                    'user_id' => $user->id,
                ]);
            }
        }

        return $next($request);
    }
}
