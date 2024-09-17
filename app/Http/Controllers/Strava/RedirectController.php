<?php

namespace App\Http\Controllers\Strava;

class RedirectController
{
    public function __invoke()
    {
        $query = http_build_query([
            'client_id'     => config('services.strava.client_id'),
            'redirect_uri'  => route('strava:callback'),
            'response_type' => 'code',
            'scope'         => 'read,activity:read_all',
        ]);

        return redirect('https://www.strava.com/oauth/authorize?' . $query);
    }
}
