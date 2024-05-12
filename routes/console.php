<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('strava:token-refresh')->everyFourHours();

Schedule::command('sync')->everyFifteenMinutes();
