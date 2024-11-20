<?php

namespace App\Filament\Resources\RidesResource\Pages;

use App\Filament\Resources\RideResource\Widgets\RidingStreakWidget;
use App\Filament\Resources\RidesResource;
use App\Jobs\SyncActivitiesJob;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListRides extends ListRecords
{
    protected static string $resource = RidesResource::class;

    public function notifySyncCompleted(): void
    {
        Notification::make()
            ->title('Sync Completed')
            ->body('The sync process has been completed successfully.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('sync')
                ->label('Sync Rides')
                ->color('primary')
                ->icon('heroicon-o-arrows-up-down')
                ->action(function () {
                    SyncActivitiesJob::dispatch();
                    Notification::make()
                        ->title('Sync Initiated')
                        ->body('The sync process has started. You will be notified once it completes.')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Sync Rides')
                ->modalSubheading('Are you sure you want to start the sync process? This may take a few minutes.')
                ->modalButton('Start Sync'),
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            RidingStreakWidget::class,
        ];
    }

    protected function getListeners(): array
    {
        return array_merge(parent::getListeners(), [
            'syncCompleted' => 'notifySyncCompleted',
        ]);
    }
}
