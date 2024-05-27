<?php

namespace App\Filament\Resources\RideResource\Pages;

use App\Filament\Resources\RideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRide extends EditRecord
{
    protected static string $resource = RideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
