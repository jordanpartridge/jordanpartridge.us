<?php

namespace App\Filament\Pages;

use App\Settings\FeaturedPodcastSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageFeaturedPodcast extends SettingsPage
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = FeaturedPodcastSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),

                TextInput::make('url')
                    ->type('url')
                    ->label('url')
                    ->helperText('This should be a youtube embed url')
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->helperText('Short Description shown underneath the video')
                    ->required(),

            ]);
    }
}
