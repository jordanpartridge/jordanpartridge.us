<?php

namespace App\Console\Commands;

use App\Http\Integrations\Strava\Requests\TokenExchange;
use App\Http\Integrations\Strava\Strava;
use App\Models\StravaToken;
use Illuminate\Console\Command;

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
            $strava = new Strava();
            $response = new TokenExchange($token->refresh_token, 'refresh_token');

            $response = $strava->send($response)->json();
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
