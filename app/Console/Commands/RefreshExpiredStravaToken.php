<?php

namespace App\Console\Commands;

use App\Models\StravaToken;
use Illuminate\Console\Command;
use JordanPartridge\StravaClient\Facades\StravaClient;

class RefreshExpiredStravaToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:token-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh expired Strava tokens.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        activity('strava:token-refresh')->log('started');
        // Get all expired tokens
        $tokens = StravaToken::all();

        // Refresh each token
        $tokens->each(function (StravaToken $token) {



            $response = StravaClient::exchangeToken($token->refresh_token, 'refresh_token');

            activity('strava:token-refresh')
                ->withProperties(['response' => $response])
                ->log('strava response');

            $token->update([
                'access_token'  => $response['access_token'],
                'expires_at'    => now()->addSeconds($response['expires_in']),
                'refresh_token' => $response['refresh_token'],
            ]);

            activity('strava:token-refresh')->log('completed');
        });
    }
}
