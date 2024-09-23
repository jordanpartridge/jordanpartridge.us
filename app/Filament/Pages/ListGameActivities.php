<?php

namespace App\Filament\Pages;

use App\Filament\Resources\GameResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListGameActivities extends ListActivities
{
    protected static string $resource = GameResource::class;

}
