<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GmailLabelsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static string $view = 'filament.pages.gmail-labels-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Labels';

    protected static ?int $navigationSort = 92;

    protected static bool $shouldRegisterNavigation = true;

    public $labels = [];
    public $systemLabels = [];
    public $userLabels = [];
    public $searchTerm = '';
    public $selectedLabelType = 'all';

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('searchTerm')
                    ->label('Search Labels')
                    ->placeholder('Search by label name...')
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->searchTerm = $state;
                        $this->filterLabels();
                    }),
            ])
            ->statePath('data');
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
            $gmailClient = $user->getGmailClient();
            $gmailLabels = $gmailClient->listLabels();

            $this->labels = $gmailLabels->map(function ($label) {
                if (is_array($label)) {
                    return [
                        'id'             => $label['id'] ?? '',
                        'name'           => $label['name'] ?? '',
                        'type'           => $label['type'] ?? 'user',
                        'messagesTotal'  => $label['messagesTotal'] ?? 0,
                        'messagesUnread' => $label['messagesUnread'] ?? 0,
                        'threadsTotal'   => $label['threadsTotal'] ?? 0,
                        'threadsUnread'  => $label['threadsUnread'] ?? 0,
                    ];
                }

                return [
                    'id'             => $label->id,
                    'name'           => $label->name,
                    'type'           => $label->type ?? 'user',
                    'messagesTotal'  => $label->messagesTotal ?? 0,
                    'messagesUnread' => $label->messagesUnread ?? 0,
                    'threadsTotal'   => $label->threadsTotal ?? 0,
                    'threadsUnread'  => $label->threadsUnread ?? 0,
                ];
            })->toArray();

            $this->filterLabels();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error fetching labels')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->redirect(GmailIntegrationPage::getUrl());
        }
    }

    public function filterLabels()
    {
        $filtered = collect($this->labels);

        // Filter by search term
        if (!empty($this->searchTerm)) {
            $filtered = $filtered->filter(function ($label) {
                return str_contains(strtolower($label['name']), strtolower($this->searchTerm));
            });
        }

        // Separate system and user labels
        $this->systemLabels = $filtered->where('type', 'system')->values()->toArray();
        $this->userLabels = $filtered->where('type', 'user')->values()->toArray();
    }

    public function selectLabelType(string $type)
    {
        $this->selectedLabelType = $type;
    }

    public function viewLabelMessages(string $labelId, string $labelName)
    {
        // Redirect to messages page with label filter
        return redirect()->route('filament.admin.pages.gmail-messages-page', [
            'selectedLabel' => $labelId
        ]);
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
