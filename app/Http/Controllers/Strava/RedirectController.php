<?php

namespace App\Http\Controllers\Strava;

class RedirectController
{
    public function __invoke()
    {
        $client_id = config('services.strava.client_id');
        $redirect_uri = route('strava:callback');

        if (! $client_id) {
            return response('Strava client id not set', 500);
        }

        if (! $redirect_uri) {
            return response('route: `strava:callback` doe not exist', 500);
        }

        $query = http_build_query([
            'client_id'     => config('services.strava.client_id'),
            'redirect_uri'  => route('strava:callback'),
            'response_type' => 'code',
            'scope'         => config('services.strava.scope'),
        ]);

        return redirect(config('services.strava.authorize_url') . '?' . $query);
    }
}
