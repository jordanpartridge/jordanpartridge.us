<?php

namespace App\Filament\Pages;

use App\Settings\GitHubSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class GitHubSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'GitHub Settings';
    protected static ?string $title = 'GitHub Settings';
    protected static string $view = 'filament.pages.github-settings';


    public ?string $token = null;
    public string $username;

    protected GitHubSettings $settings;

    public function __construct()
    {
        $this->settings = app(GitHubSettings::class);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // Check if user has admin role or super admin role
        return $user->hasRole(['admin', 'super_admin']);
    }

    public static function getSlug(): string
    {
        return 'settings/github';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // Check if user has admin role or super admin role
        return $user->hasRole(['admin', 'super_admin']);
    }

    public function mount(): void
    {
        $this->token = $this->settings->getToken();
        $this->username = $this->settings->username;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('GitHub API Configuration')
                    ->description('Configure your GitHub API integration')
                    ->schema([
                        TextInput::make('token')
                            ->label('GitHub API Token')
                            ->password()
                            ->revealable()
                            ->required()
                            ->helperText('Generate a token at https://github.com/settings/tokens'),
                        TextInput::make('username')
                            ->label('GitHub Username')
                            ->required(),
                        View::make('filament.pages.partials.github-token-instructions'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Update settings
        $this->settings->setToken($data['token']);
        $this->settings->username = $data['username'];
        $this->settings->save();

        Notification::make()
            ->title('GitHub settings updated successfully')
            ->success()
            ->send();
    }
}
