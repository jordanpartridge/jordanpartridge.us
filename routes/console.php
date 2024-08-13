<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('strava:token-refresh')->hourly()->withoutOverlapping();

Schedule::command('sync')->hourly()->withoutOverlapping();
