<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

// No need for Session facade import

class GmailLabelsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static string $view = 'filament.pages.gmail-labels-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Labels';

    protected static ?int $navigationSort = 92;

    // Remove from navigation menu, we'll navigate to it from the GmailIntegrationPage
    protected static bool $shouldRegisterNavigation = false;

    public $labels = [];

    public function getHeading(): string
    {
        return 'Gmail Labels';
    }

    public function getSubheading(): string
    {
        return 'View and manage Gmail labels';
    }

    public function mount(): void
    {
        // No specific authorization required
        $this->loadLabels();
    }

    public function loadLabels()
    {
        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();

            $this->redirect(GmailIntegrationPage::getUrl());
            return;
        }

        try {
            // Get the Gmail client
            $gmailClient = $user->getGmailClient();

            // List labels
            $this->labels = $gmailClient->listLabels();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error fetching labels')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->redirect(GmailIntegrationPage::getUrl());
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Gmail Dashboard')
                ->color('gray')
                ->url(GmailIntegrationPage::getUrl()),

            Action::make('refresh')
                ->label('Refresh Labels')
                ->color('warning')
                ->action(fn () => $this->loadLabels()),
        ];
    }
}
