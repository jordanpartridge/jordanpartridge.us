<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('strava:token-refresh')->everyFourHours()->withoutOverlapping();

Schedule::command('sync')->hourly()->withoutOverlapping();
