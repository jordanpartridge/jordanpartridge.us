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
    protected $signature = 'strava:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh expired Strava tokens.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all expired tokens
        $tokens = StravaToken::all();

        // Refresh each token
        $tokens->each(function (StravaToken $token) {
            $strava = new Strava();
            $response = new TokenExchange($token->refresh_token, 'refresh_token');

            $response = $strava->send($response)->json();

            $token->update([
                'access_token'  => $response['access_token'],
                'expires_at'    => now()->addSeconds($response['expires_in']),
                'refresh_token' => $response['refresh_token'],
            ]);
        });
    }
}
