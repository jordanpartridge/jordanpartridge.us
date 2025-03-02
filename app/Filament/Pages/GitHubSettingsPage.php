<?php

namespace App\Filament\Pages;

use App\Settings\GitHubSettings;
use Filament\Forms;
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
    
    public static function getSlug(): string
    {
        return 'settings/github';
    }
    
    public ?string $token = null;
    public string $username;
    
    protected GitHubSettings $settings;
    
    public function __construct()
    {
        $this->settings = app(GitHubSettings::class);
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
                Forms\Components\Section::make('GitHub API Configuration')
                    ->description('Configure your GitHub API integration')
                    ->schema([
                        Forms\Components\TextInput::make('token')
                            ->label('GitHub API Token')
                            ->password()
                            ->revealable()
                            ->required()
                            ->helperText('Generate a token at https://github.com/settings/tokens'),
                            
                        Forms\Components\TextInput::make('username')
                            ->label('GitHub Username')
                            ->required(),
                            
                        Forms\Components\View::make('filament.pages.partials.github-token-instructions'),
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