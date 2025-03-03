<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use App\Models\GithubRepository;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGithubRepositories extends ListRecords
{
    protected static string $resource = GithubRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('sync_all')
                ->label('Sync All Repositories')
                ->color('primary')
                ->action(function () {
                    try {
                        $repositories = GithubRepository::all();
                        foreach ($repositories as $repository) {
                            // Sync logic here
                        }

                        Notification::make()
                            ->title('All Repositories Synced Successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Sync Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
