<?php

namespace App\Http\Controllers\Strava;

use App\Http\Integrations\Strava\Requests\TokenExchange;
use App\Http\Integrations\Strava\Strava;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class CallbackController
{
    public function __construct(private readonly Strava $strava)
    {
    }

    public function __invoke(Request $request)
    {

        if (! $request->has('code')) {
            return response(['error' => 'No code provided'], 400);
        }

        $tokenExchange = new TokenExchange($request->input('code'));

        $response = $this->strava->send($tokenExchange);

        if (! $response->ok()) {
            return response(['error' => 'Failed to exchange token'], 500);
        }

        $data = $response->json();

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
