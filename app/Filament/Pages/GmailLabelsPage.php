<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

class GmailLabelsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static string $view = 'filament.pages.gmail-labels-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Labels';

    protected static ?int $navigationSort = 92;

    protected static bool $shouldRegisterNavigation = true;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $labels = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $systemLabels = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $userLabels = [];

    public string $searchTerm = '';

    public string $selectedLabelType = 'all';

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
                    ->afterStateUpdated(function ($state): void {
                        $this->searchTerm = (string) $state;
                        $this->filterLabels();
                    }),
            ])
            ->statePath('data');
    }

    public function loadLabels(): void
    {
        $user = auth()->user();

        if (!$user || !$user->hasValidGmailToken()) {
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

            // CRITICAL FIX: Enhanced Livewire serialization handling
            $this->labels = $gmailLabels->map(function ($label): array {
                // Ensure all data is properly serializable for Livewire
                if (is_array($label)) {
                    return [
                        'id'             => (string) ($label['id'] ?? ''),
                        'name'           => (string) ($label['name'] ?? ''),
                        'type'           => (string) ($label['type'] ?? 'user'),
                        'messagesTotal'  => (int) ($label['messagesTotal'] ?? 0),
                        'messagesUnread' => (int) ($label['messagesUnread'] ?? 0),
                        'threadsTotal'   => (int) ($label['threadsTotal'] ?? 0),
                        'threadsUnread'  => (int) ($label['threadsUnread'] ?? 0),
                    ];
                }

                // Handle object conversion with explicit type casting
                return [
                    'id'             => (string) ($label->id ?? ''),
                    'name'           => (string) ($label->name ?? ''),
                    'type'           => (string) ($label->type ?? 'user'),
                    'messagesTotal'  => (int) ($label->messagesTotal ?? 0),
                    'messagesUnread' => (int) ($label->messagesUnread ?? 0),
                    'threadsTotal'   => (int) ($label->threadsTotal ?? 0),
                    'threadsUnread'  => (int) ($label->threadsUnread ?? 0),
                ];
            })->toArray(); // Ensure final result is a plain array

            // Verify serialization compatibility
            $this->validateLabelsData();

            $this->filterLabels();

            Log::info('Gmail labels loaded successfully', [
                'total_labels' => count($this->labels),
                'system_count' => collect($this->labels)->where('type', 'system')->count(),
                'user_count'   => collect($this->labels)->where('type', 'user')->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Gmail labels loading failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Error fetching labels')
                ->body('Failed to load Gmail labels: ' . $e->getMessage())
                ->danger()
                ->send();

            // Fallback to empty arrays to prevent further issues
            $this->labels = [];
            $this->systemLabels = [];
            $this->userLabels = [];

            $this->redirect(GmailIntegrationPage::getUrl());
        }
    }

    public function filterLabels(): void
    {
        try {
            $filtered = collect($this->labels);

            // Filter by search term
            if (!empty($this->searchTerm)) {
                $filtered = $filtered->filter(function (array $label): bool {
                    return str_contains(strtolower($label['name'] ?? ''), strtolower($this->searchTerm));
                });
            }

            // CRITICAL FIX: Ensure filtered data is properly serializable
            $this->systemLabels = $filtered
                ->where('type', 'system')
                ->values()
                ->map(fn (array $label): array => $this->sanitizeLabelForLivewire($label))
                ->toArray();

            $this->userLabels = $filtered
                ->where('type', 'user')
                ->values()
                ->map(fn (array $label): array => $this->sanitizeLabelForLivewire($label))
                ->toArray();

            Log::info('Labels filtered successfully', [
                'search_term'   => $this->searchTerm,
                'system_labels' => count($this->systemLabels),
                'user_labels'   => count($this->userLabels)
            ]);

        } catch (\Exception $e) {
            Log::error('Label filtering failed', [
                'error'       => $e->getMessage(),
                'search_term' => $this->searchTerm
            ]);

            // Fallback to prevent crashes
            $this->systemLabels = [];
            $this->userLabels = [];
        }
    }

    public function selectLabelType(string $type): void
    {
        $this->selectedLabelType = $type;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function viewLabelMessages(string $labelId, string $labelName)
    {
        // Sanitize parameters before redirect
        $cleanLabelId = filter_var($labelId, FILTER_SANITIZE_STRING) ?: '';
        $cleanLabelName = filter_var($labelName, FILTER_SANITIZE_STRING) ?: '';

        Log::info('Redirecting to messages page', [
            'label_id'   => $cleanLabelId,
            'label_name' => $cleanLabelName
        ]);

        // Redirect to messages page with label filter
        return redirect()->route('filament.admin.pages.gmail-messages-page', [
            'selectedLabel' => $cleanLabelId
        ]);
    }

    /**
     * CRITICAL FIX: Handle potential serialization issues during Livewire updates
     */
    public function dehydrate(): void
    {
        // Ensure all properties are properly serializable before Livewire dehydration
        try {
            $this->labels = array_map([$this, 'sanitizeLabelForLivewire'], $this->labels);
            $this->systemLabels = array_map([$this, 'sanitizeLabelForLivewire'], $this->systemLabels);
            $this->userLabels = array_map([$this, 'sanitizeLabelForLivewire'], $this->userLabels);
        } catch (\Exception $e) {
            Log::error('Dehydration error in GmailLabelsPage', [
                'error' => $e->getMessage()
            ]);

            // Reset to safe state
            $this->labels = [];
            $this->systemLabels = [];
            $this->userLabels = [];
        }
    }

    /**
     * CRITICAL FIX: Handle potential serialization issues during Livewire hydration
     */
    public function hydrate(): void
    {
        // Ensure all properties are properly formatted after Livewire hydration
        try {
            if (!is_array($this->labels)) {
                $this->labels = [];
            }
            if (!is_array($this->systemLabels)) {
                $this->systemLabels = [];
            }
            if (!is_array($this->userLabels)) {
                $this->userLabels = [];
            }

            // Validate data integrity
            $this->validateLabelsData();
        } catch (\Exception $e) {
            Log::error('Hydration error in GmailLabelsPage', [
                'error' => $e->getMessage()
            ]);

            // Reset to safe state
            $this->labels = [];
            $this->systemLabels = [];
            $this->userLabels = [];
        }
    }

    /**
     * @return array<int, Action>
     */
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
                ->action(fn (): void => $this->loadLabels()),
        ];
    }

    /**
     * CRITICAL FIX: Sanitize label data for Livewire compatibility
     * 
     * @param array<string, mixed> $label
     * @return array<string, mixed>
     */
    private function sanitizeLabelForLivewire(array $label): array
    {
        return [
            'id'             => (string) ($label['id'] ?? ''),
            'name'           => (string) ($label['name'] ?? ''),
            'type'           => (string) ($label['type'] ?? 'user'),
            'messagesTotal'  => (int) ($label['messagesTotal'] ?? 0),
            'messagesUnread' => (int) ($label['messagesUnread'] ?? 0),
            'threadsTotal'   => (int) ($label['threadsTotal'] ?? 0),
            'threadsUnread'  => (int) ($label['threadsUnread'] ?? 0),
        ];
    }

    /**
     * CRITICAL FIX: Validate that labels data is properly serializable
     */
    private function validateLabelsData(): void
    {
        foreach ($this->labels as $index => $label) {
            if (!is_array($label)) {
                Log::warning("Non-array label found at index {$index}", [
                    'label_type' => gettype($label),
                    'label_data' => is_object($label) ? get_class($label) : $label
                ]);

                // Convert to array if it's an object
                if (is_object($label)) {
                    $this->labels[$index] = $this->sanitizeLabelForLivewire((array) $label);
                } else {
                    // Remove invalid entries
                    unset($this->labels[$index]);
                }
            }

            // Ensure all required fields exist and are proper types
            if (isset($this->labels[$index])) {
                $this->labels[$index] = $this->sanitizeLabelForLivewire($this->labels[$index]);
            }
        }

        // Re-index array to prevent gaps
        $this->labels = array_values($this->labels);

        Log::info('Labels data validation completed', [
            'total_labels' => count($this->labels)
        ]);
    }
}
