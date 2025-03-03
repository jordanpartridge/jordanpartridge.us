<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditGithubRepository extends EditRecord
{
    protected static string $resource = GithubRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')
                ->label('Sync Repository')
                ->color('primary')
                ->action(function () {
                    try {
                        $record = $this->record;
                        // Sync logic here
                        Notification::make()
                            ->title('Repository Synced Successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Sync Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            DeleteAction::make()
        ];
    }
}
