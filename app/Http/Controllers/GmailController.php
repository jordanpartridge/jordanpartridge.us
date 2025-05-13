<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PartridgeRocks\GmailClient\Facades\GmailClient;

class GmailController extends Controller
{
    /**
     * Show the Gmail dashboard.
     */
    public function index()
    {
        return view('gmail.index');
    }

    /**
     * Manually initiate the Gmail authentication process.
     */
    public function authenticate()
    {
        $authUrl = GmailClient::getAuthorizationUrl(
            config('gmail-client.redirect_uri'),
            config('gmail-client.scopes')
        );

        return redirect($authUrl);
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');

        // Exchange code for tokens
        $tokens = GmailClient::exchangeCode(
            $code,
            config('gmail-client.redirect_uri')
        );

        // Store tokens in session for demo purposes
        // In a real app, you would store these securely for the authenticated user
        session([
            'gmail_access_token'     => $tokens['access_token'],
            'gmail_refresh_token'    => $tokens['refresh_token'] ?? null,
            'gmail_token_expires_at' => now()->addSeconds($tokens['expires_in'])->toDateTimeString(),
        ]);

        return redirect()->route('gmail.index')->with('success', 'Successfully authenticated with Gmail!');
    }

    /**
     * List messages from Gmail.
     */
    public function listMessages()
    {
        if (!session('gmail_access_token')) {
            return redirect()->route('gmail.index')->with('error', 'Not authenticated with Gmail.');
        }

        try {
            // Authenticate with stored token
            GmailClient::authenticate(session('gmail_access_token'));

            // List recent messages
            $messages = GmailClient::listMessages(['maxResults' => 10]);

            return view('gmail.messages', ['messages' => $messages]);
        } catch (\Exception $e) {
            // Handle authentication or API errors
            return redirect()->route('gmail.index')->with('error', 'Error fetching Gmail messages: ' . $e->getMessage());
        }
    }

    /**
     * List labels from Gmail.
     */
    public function listLabels()
    {
        if (!session('gmail_access_token')) {
            return redirect()->route('gmail.index')->with('error', 'Not authenticated with Gmail.');
        }

        try {
            // Authenticate with stored token
            GmailClient::authenticate(session('gmail_access_token'));

            // List labels
            $labels = GmailClient::listLabels();

            return view('gmail.labels', ['labels' => $labels]);
        } catch (\Exception $e) {
            // Handle authentication or API errors
            return redirect()->route('gmail.index')->with('error', 'Error fetching Gmail labels: ' . $e->getMessage());
        }
    }
}
