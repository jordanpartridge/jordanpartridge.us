<?php

namespace App\Http\Controllers\Strava;

use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use JordanPartridge\StravaClient\Facades\StravaClient;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class CallbackController
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function __invoke(Request $request)
    {

        if (! $request->has('code')) {
            return response(['error' => 'No code provided'], 400);
        }


        $data = StravaClient::exchangeToken($request->input('code'));

        $request->user()->stravaToken()->create([
            'access_token'  => $data['access_token'],
            'expires_at'    => now()->addSeconds($data['expires_in']),
            'refresh_token' => $data['refresh_token'],
            'athlete_id'    => $data['athlete']['id'],
        ]);

        Notification::make()
            ->title('Strava Connected')
            ->success()
            ->send();

        return redirect('/admin');
    }
}
