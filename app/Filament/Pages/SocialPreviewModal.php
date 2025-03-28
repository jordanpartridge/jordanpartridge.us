<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SocialPreviewModal extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.social-preview-modal';

    protected static bool $shouldRegisterNavigation = false;

    public $linkedinContent;
    public $twitterContent;

    public function mount($linkedinContent = null, $twitterContent = null)
    {
        $this->linkedinContent = $linkedinContent;
        $this->twitterContent = $twitterContent;
    }
}
