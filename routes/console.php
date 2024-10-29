<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sync')->hourly()->withoutOverlapping();

Schedule::command('activitylog:clean')->daily();
