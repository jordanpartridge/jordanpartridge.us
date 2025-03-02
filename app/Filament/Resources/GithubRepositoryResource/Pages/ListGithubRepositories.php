<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGithubRepositories extends ListRecords
{
    protected static string $resource = GithubRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync_all')
                ->label('Sync All Repositories')
                ->icon('heroicon-o-arrow-path')
                ->action(function (): void {
                    $syncService = app(\App\Services\GitHub\GitHubSyncService::class);
                    
                    try {
                        $result = $syncService->syncAllRepositories();
                        
                        \Filament\Notifications\Notification::make()
                            ->title("Synced {$result->count()} repositories")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        if (strpos($e->getMessage(), 'GitHub API token not set') !== false) {
                            // Token not set error
                            \Filament\Notifications\Notification::make()
                                ->title('GitHub API token not set')
                                ->body('You need to set up your GitHub API token in GitHub Settings')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('settings')
                                        ->label('Go to Settings')
                                        ->url(route('filament.admin.pages.settings.github')),
                                ])
                                ->danger()
                                ->persistent()
                                ->send();
                        } else if (strpos($e->getMessage(), 'No active repositories found') !== false) {
                            // No repositories error
                            \Filament\Notifications\Notification::make()
                                ->title('No active repositories')
                                ->body('Add repositories through the Create button first')
                                ->warning()
                                ->send();
                        } else {
                            // Generic error
                            \Filament\Notifications\Notification::make()
                                ->title('Error syncing repositories')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }
                })
                ->requiresConfirmation()
                ->modalDescription('This will sync all active repositories with GitHub'),
            Actions\CreateAction::make(),
        ];
    }
}