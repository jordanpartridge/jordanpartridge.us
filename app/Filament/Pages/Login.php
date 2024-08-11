<?php

namespace App\Filament\Pages;

class Login extends \Filament\Pages\Auth\Login
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.login';
}
