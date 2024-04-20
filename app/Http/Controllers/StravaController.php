<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Strava\Requests\TokenExchange;
use App\Http\Integrations\Strava\Strava;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class StravaController extends Controller
{
    /**
     * Redirect to Strava.
     */
    public function redirect(): Application|Redirector|RedirectResponse
    {
        $query = http_build_query([
            'client_id'     => config('services.strava.client_id'),
            'redirect_uri'  => route('strava.callback'),
            'response_type' => 'code',
            'scope'         => 'read,activity:read_all',
        ]);

        return redirect('https://www.strava.com/oauth/authorize?' . $query);
    }

    /**
     * Handle the Strava callback.
     *
     * @throws FatalRequestException
     * @throws RequestException|\JsonException
     */
    public function callback(Request $request): RedirectResponse
    {
        $strava = new Strava();

        $tokenExchange = new TokenExchange($request->code);

        $response = $strava->send($tokenExchange);

        $data = $response->json();

        $request->user()->stravaToken()->create([
            'access_token'  => $data['access_token'],
            'expires_at'    => now()->addSeconds($data['expires_in']),
            'refresh_token' => $data['refresh_token'],
            'athlete_id'    => $data['athlete']['id'],
        ]);

        // Redirect to the dashboard
        return redirect()->route('profile.edit');
    }
}
