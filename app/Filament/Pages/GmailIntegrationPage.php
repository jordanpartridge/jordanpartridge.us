<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PartridgeRocks\GmailClient\Facades\GmailClient;

class GmailIntegrationPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.gmail-integration-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Integration';

    protected static ?int $navigationSort = 90;

    // Properties for token information
    public $accessToken = null;
    public $refreshToken = null;
    public $tokenExpires = null;

    public function getHeading(): string
    {
        return 'Gmail Integration';
    }

    public function getSubheading(): string
    {
        return 'Test and manage Gmail API integration';
    }

    public function mount(): void
    {
        // No specific authorization required
    }

    /**
     * Check if the user is authenticated with Gmail
     */
    public function isAuthenticated(): bool
    {
        $user = auth()->user();
        $token = $user->gmailToken;

        Log::info('Gmail authentication check', [
            'user_id'       => $user->id,
            'has_token'     => $token ? 'Yes' : 'No',
            'token_expired' => $token && $token->isExpired() ? 'Yes' : 'No',
        ]);

        // Debug information - will display in your filament page
        $this->accessToken = $token ? Str::limit($token->access_token, 30) : null;
        $this->refreshToken = $token ? 'Present' : null;
        $this->tokenExpires = $token ? $token->expires_at : null;

        return $user->hasValidGmailToken();
    }

    /**
     * Get the authentication status message
     */
    public function getAuthStatusMessage(): string
    {
        $token = auth()->user()->gmailToken;

        if ($this->isAuthenticated()) {
            return 'Authenticated with Gmail. Token expires: ' . $token->expires_at->format('M d, Y h:i A');
        }

        if ($token && $token->isExpired()) {
            return 'Gmail token has expired. Please re-authenticate.';
        }

        return 'Not authenticated with Gmail. Click "Authenticate" to connect.';
    }

    /**
     * Get messages from Gmail - for demonstration purposes
     */
    public function getMessages()
    {
        if (!$this->isAuthenticated()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();

            return [];
        }

        try {
            // Get the Gmail client from the user
            $gmailClient = auth()->user()->getGmailClient();

            // List recent messages
            return $gmailClient->listMessages(['maxResults' => 5]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error fetching messages')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return [];
        }
    }

    /**
     * Get the authentication URL
     */
    protected function authenticateAction(): Action
    {
        return Action::make('authenticate')
            ->label('Authenticate with Gmail')
            ->color('primary')
            ->url(function (): string {
                return GmailClient::getAuthorizationUrl(
                    config('gmail-client.redirect_uri'),
                    config('gmail-client.scopes')
                );
            });
    }

    /**
     * List messages action
     */
    protected function listMessagesAction(): Action
    {
        return Action::make('listMessages')
            ->label('List Recent Messages')
            ->color('success')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-messages');
            })
            ->disabled(fn (): bool => !$this->isAuthenticated());
    }

    /**
     * List labels action
     */
    protected function listLabelsAction(): Action
    {
        return Action::make('listLabels')
            ->label('List Labels')
            ->color('warning')
            ->action(function () {
                return $this->redirectRoute('filament.admin.pages.gmail-labels');
            })
            ->disabled(fn (): bool => !$this->isAuthenticated());
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->authenticateAction(),
            $this->listMessagesAction(),
            $this->listLabelsAction(),
        ];
    }
}
