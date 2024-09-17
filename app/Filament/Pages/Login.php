<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as FilamentLogin;

class Login extends FilamentLogin
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.login';
}
