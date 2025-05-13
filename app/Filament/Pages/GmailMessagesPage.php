<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

// No need for Session facade import

class GmailMessagesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static string $view = 'filament.pages.gmail-messages-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Messages';

    protected static ?int $navigationSort = 91;

    // Remove from navigation menu, we'll navigate to it from the GmailIntegrationPage
    protected static bool $shouldRegisterNavigation = false;

    public $messages = [];

    public function getHeading(): string
    {
        return 'Gmail Messages';
    }

    public function getSubheading(): string
    {
        return 'View recent messages from Gmail';
    }

    public function mount(): void
    {
        // No specific authorization required
        $this->loadMessages();
    }

    public function loadMessages($maxResults = 10)
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

            // List recent messages
            $this->messages = $gmailClient->listMessages(['maxResults' => $maxResults]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error fetching messages')
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
                ->label('Refresh Messages')
                ->color('warning')
                ->action(fn () => $this->loadMessages()),
        ];
    }
}
