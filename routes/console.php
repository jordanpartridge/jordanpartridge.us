<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('strava:token-refresh')->everyFourHours();

Schedule::command('strava:activities-sync')->everyFifteenMinutes();
