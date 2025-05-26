<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\ClientEmail;
use App\Models\Post;
use App\Models\GithubRepository;
use App\Models\Ride;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GmailMessagesPage extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static string $view = 'filament.pages.gmail-messages-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Messages';

    protected static ?int $navigationSort = 91;

    // Keep navigation enabled so the route gets registered, but we can hide it with navigation sort
    protected static bool $shouldRegisterNavigation = true;

    public $messages = [];
    public $filter = 'all';
    public $searchTerm = '';
    public $selectedCategory = 'all';
    public $selectedLabel = 'INBOX';
    public $availableLabels = [];
    public $clients = [];
    public $projects = [];
    public $recentActivity = [];

    // Form data
    public ?array $data = [];

    // Modal state
    public $showingEmailId = null;
    public $emailPreview = null;

    // Hover preview state
    public $hoveredEmailId = null;
    public $hoverPreview = null;

    // Expanded message state
    public $expandedMessageIds = [];

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
        $this->loadLabels();
        $this->loadClients();
        $this->loadProjects();
        $this->loadRecentActivity();

        // Initialize form data
        $this->form->fill([
            'selectedLabel' => $this->selectedLabel,
            'searchTerm'    => $this->searchTerm,
        ]);

        $this->loadMessages();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedLabel')
                    ->label('Filter by Label')
                    ->options(function () {
                        $options = ['INBOX' => 'Inbox'];
                        foreach ($this->availableLabels as $label) {
                            $options[$label['id']] = $label['name'];
                        }
                        return $options;
                    })
                    ->default($this->selectedLabel)
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedLabel = $state;
                        $this->loadMessages();
                    }),

                TextInput::make('searchTerm')
                    ->label('Search')
                    ->placeholder('Search emails...')
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->searchTerm = $state;
                        $this->loadMessages();
                    }),
            ])
            ->statePath('data');
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

            // Get Gmail messages for selected label with search
            $queryParams = [
                'maxResults' => $maxResults,
                'labelIds'   => [$this->selectedLabel]
            ];

            // Add search query if provided
            if (!empty($this->searchTerm)) {
                $queryParams['q'] = $this->searchTerm;
            }

            $rawMessages = $gmailClient->listMessages($queryParams);

            // Convert Gmail Email objects to simple arrays for Livewire
            $this->messages = $rawMessages->map(function ($email) {
                return [
                    'id'          => $email->id,
                    'from'        => html_entity_decode($email->from ?? 'Unknown', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'from_name'   => $email->from ? (strpos($email->from, '<') !== false ? html_entity_decode(substr($email->from, 0, strpos($email->from, '<')), ENT_QUOTES | ENT_HTML5, 'UTF-8') : '') : '',
                    'subject'     => html_entity_decode($email->subject ?? 'No Subject', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'snippet'     => html_entity_decode($email->snippet ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'date'        => $email->internalDate ? $email->internalDate->toISOString() : now()->toISOString(),
                    'isRead'      => !in_array('UNREAD', $email->labelIds ?? []),
                    'isImportant' => in_array('IMPORTANT', $email->labelIds ?? []),
                    'isStarred'   => in_array('STARRED', $email->labelIds ?? []),
                    'labels'      => $email->labelIds ?? [],
                    'body_text'   => html_entity_decode($email->body->text ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'body_html'   => $email->body->html ?? '', // HTML body should already be properly encoded
                    // AI analysis will be added here
                    'isClient'         => false, // Will be determined by AI
                    'category'         => 'unknown', // Will be categorized by AI
                    'urgency'          => 'normal', // Will be analyzed by AI
                    'contains_invoice' => false, // Will be detected by AI
                    'contains_payment' => false, // Will be detected by AI
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Gmail fetch error: ' . $e->getMessage());

            Notification::make()
                ->title('Error fetching messages')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Fallback to demo data for development
            $this->loadDemoMessages();
        }
    }

    /**
     * Load available Gmail labels for filtering
     */
    public function loadLabels()
    {
        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            return;
        }

        try {
            $gmailClient = $user->getGmailClient();
            $gmailLabels = $gmailClient->listLabels();

            $this->availableLabels = $gmailLabels->map(function ($label) {
                if (is_array($label)) {
                    return [
                        'id'   => $label['id'] ?? '',
                        'name' => $label['name'] ?? '',
                        'type' => $label['type'] ?? 'user',
                    ];
                }

                return [
                    'id'   => $label->id,
                    'name' => $label->name,
                    'type' => $label->type ?? 'user',
                ];
            })->toArray();

        } catch (\Exception $e) {
            // Fail silently for labels, don't break the page
            $this->availableLabels = [];
        }
    }

    /**
     * Load clients for intelligent email matching
     */
    public function loadClients()
    {
        $this->clients = Client::all(['id', 'name', 'email', 'company', 'status', 'website'])
            ->keyBy('email')
            ->toArray();
    }

    /**
     * Load projects/posts for context linking
     */
    public function loadProjects()
    {
        $this->projects = Post::published()
            ->typePost()
            ->latest()
            ->take(10)
            ->get(['id', 'title', 'slug', 'created_at'])
            ->toArray();
    }

    /**
     * Load recent activity for timeline integration
     */
    public function loadRecentActivity()
    {
        $activities = collect();

        // Recent GitHub commits (if available)
        $recentRepos = GithubRepository::where('is_active', true)
            ->orderBy('last_fetched_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentRepos as $repo) {
            $activities->push([
                'type'        => 'github_commit',
                'title'       => "Updated {$repo->name}",
                'description' => $repo->description ?? 'Repository activity',
                'date'        => $repo->last_fetched_at ?? $repo->updated_at,
                'icon'        => 'code',
                'color'       => 'purple'
            ]);
        }

        // Recent rides/activities
        $recentRides = Ride::latest()
            ->take(3)
            ->get(['id', 'name', 'distance', 'created_at']);

        foreach ($recentRides as $ride) {
            $activities->push([
                'type'        => 'strava_activity',
                'title'       => $ride->name ?? 'Cycling Activity',
                'description' => "Rode {$ride->distance}km",
                'date'        => $ride->created_at,
                'icon'        => 'bike',
                'color'       => 'orange'
            ]);
        }

        $this->recentActivity = $activities->sortByDesc('date')->take(8)->values()->toArray();
    }

    /**
     * Sync clients from emails
     */
    public function syncClientsFromEmails()
    {
        $newProspects = 0;

        foreach ($this->messages as $message) {
            if (!$message['isClient'] && $message['category'] === 'prospect_inquiry') {
                // Create new client/prospect
                $email = $message['from'];
                $name = $message['from_name'] ?: explode('@', $email)[0];

                $existingClient = Client::where('email', $email)->first();
                if (!$existingClient) {
                    Client::create([
                        'name'            => $name,
                        'email'           => $email,
                        'status'          => 'lead',
                        'notes'           => 'Auto-created from Gmail: ' . $message['subject'],
                        'user_id'         => auth()->id(),
                        'last_contact_at' => Carbon::parse($message['date']),
                    ]);
                    $newProspects++;
                }
            }
        }

        Notification::make()
            ->title('Sync Complete')
            ->body("Added {$newProspects} new prospects to CRM")
            ->success()
            ->send();

        $this->loadClients();
        $this->loadMessages();
    }

    /**
     * Filter messages by category
     */
    public function filterBy(string $filter)
    {
        $this->filter = $filter;
        // Note: In a real implementation, you'd want to re-filter the messages array
        // For now, this is handled in the Blade template
    }

    /**
     * Change selected Gmail label and reload messages
     */
    public function selectLabel(string $labelId)
    {
        $this->selectedLabel = $labelId;
        $this->loadMessages();

        Notification::make()
            ->title('Label Changed')
            ->body("Now showing emails from: " . ($labelId === 'INBOX' ? 'Inbox' : $labelId))
            ->success()
            ->send();
    }

    /**
     * Toggle email star status
     * Now available with Gmail client package v1.0.4+ which includes label management methods
     */
    public function toggleStar(string $messageId)
    {
        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();
            return;
        }

        try {
            $gmailClient = $user->getGmailClient();

            // Find the message in our current list to check if it's starred
            $currentMessage = collect($this->messages)->firstWhere('id', $messageId);
            $isStarred = $currentMessage ? in_array('STARRED', $currentMessage['labels'] ?? []) : false;

            if ($isStarred) {
                // Remove star
                $gmailClient->removeLabelsFromMessage($messageId, ['STARRED']);
                $action = 'removed star from';
            } else {
                // Add star
                $gmailClient->addLabelsToMessage($messageId, ['STARRED']);
                $action = 'starred';
            }

            // Update the message in our current list to reflect the change immediately
            foreach ($this->messages as &$message) {
                if ($message['id'] === $messageId) {
                    if ($isStarred) {
                        $message['labels'] = array_diff($message['labels'], ['STARRED']);
                        $message['isStarred'] = false;
                    } else {
                        $message['labels'] = array_unique(array_merge($message['labels'], ['STARRED']));
                        $message['isStarred'] = true;
                    }
                    break;
                }
            }

            Notification::make()
                ->title('Success')
                ->body("Successfully {$action} email")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Log::error('Star toggle error: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('Failed to update star status: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Show email preview modal
     */
    public function showEmailPreview(string $messageId)
    {
        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();
            return;
        }

        try {
            $gmailClient = $user->getGmailClient();
            $email = $gmailClient->getMessage($messageId);

            // Debug: Log the email structure to understand the body format
            Log::info('Email structure for preview', [
                'id'             => $email->id,
                'subject'        => $email->subject,
                'body_type'      => gettype($email->body ?? null),
                'body_structure' => is_object($email->body ?? null) ? get_class($email->body) : null,
                'body_content'   => $email->body ?? null,
                'snippet'        => $email->snippet ?? null,
            ]);

            // Try different ways to get the body content
            $bodyHtml = '';
            $bodyText = '';
            $fallbackContent = '';

            if (isset($email->body)) {
                if (is_object($email->body)) {
                    $bodyHtml = $email->body->html ?? '';
                    $bodyText = $email->body->text ?? '';
                } elseif (is_string($email->body)) {
                    $bodyText = $email->body;
                } elseif (is_array($email->body)) {
                    $bodyHtml = $email->body['html'] ?? '';
                    $bodyText = $email->body['text'] ?? '';
                }
            }

            // Fallback to snippet if no body content
            if (empty($bodyHtml) && empty($bodyText)) {
                $fallbackContent = $email->snippet ?? 'No content available';
            }

            $this->emailPreview = [
                'id'        => $email->id,
                'subject'   => html_entity_decode($email->subject ?? 'No Subject', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'from'      => html_entity_decode($email->from ?? 'Unknown Sender', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'date'      => $email->internalDate ? $email->internalDate->format('M j, Y g:i A') : 'Unknown Date',
                'body_html' => $bodyHtml, // HTML should already be properly encoded
                'body_text' => html_entity_decode($bodyText, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'body'      => html_entity_decode($bodyText ?: $fallbackContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'snippet'   => html_entity_decode($email->snippet ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'labels'    => $email->labelIds ?? [],
                'isStarred' => in_array('STARRED', $email->labelIds ?? []),
                'isRead'    => !in_array('UNREAD', $email->labelIds ?? []),
            ];

            $this->showingEmailId = $messageId;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to load email: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Close email preview modal
     */
    public function closeEmailPreview()
    {
        $this->showingEmailId = null;
        $this->emailPreview = null;
    }

    /**
     * Handle keyboard shortcuts
     */
    public function getListeners(): array
    {
        return [
            'keydown.escape' => 'handleEscapeKey',
        ];
    }

    /**
     * Handle escape key press
     */
    public function handleEscapeKey()
    {
        if ($this->showingEmailId) {
            $this->closeEmailPreview();
        }
    }

    /**
     * Show hover preview for an email
     */
    public function showHoverPreview(string $messageId)
    {
        // Don't fetch if already hovering the same message
        if ($this->hoveredEmailId === $messageId) {
            return;
        }

        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            return;
        }

        try {
            $gmailClient = $user->getGmailClient();
            $email = $gmailClient->getMessage($messageId);

            $this->hoverPreview = [
                'id'        => $email->id,
                'subject'   => html_entity_decode($email->subject ?? 'No Subject', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'from'      => html_entity_decode($email->from ?? 'Unknown Sender', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'date'      => $email->internalDate ? $email->internalDate->format('M j, Y g:i A') : 'Unknown Date',
                'body_text' => html_entity_decode($email->body->text ?? 'No content available', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'body_html' => $email->body->html ?? '', // HTML should already be properly encoded
                'snippet'   => html_entity_decode($email->snippet ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'labels'    => $email->labelIds ?? [],
                'isStarred' => in_array('STARRED', $email->labelIds ?? []),
                'isRead'    => !in_array('UNREAD', $email->labelIds ?? []),
            ];

            $this->hoveredEmailId = $messageId;

        } catch (\Exception $e) {
            // Fail silently for hover preview
            Log::error('Hover preview error: ' . $e->getMessage());
        }
    }

    /**
     * Hide hover preview
     */
    public function hideHoverPreview()
    {
        $this->hoveredEmailId = null;
        $this->hoverPreview = null;
    }

    /**
     * Toggle expanded view for a message
     */
    public function toggleExpanded(string $messageId)
    {
        if (in_array($messageId, $this->expandedMessageIds)) {
            // Remove from expanded list
            $this->expandedMessageIds = array_diff($this->expandedMessageIds, [$messageId]);
        } else {
            // Add to expanded list
            $this->expandedMessageIds[] = $messageId;
        }
    }

    /**
     * Check if a message is expanded
     */
    public function isExpanded(string $messageId): bool
    {
        return in_array($messageId, $this->expandedMessageIds);
    }

    /**
     * Expand all messages
     */
    public function expandAll()
    {
        $this->expandedMessageIds = collect($this->messages)->pluck('id')->toArray();

        Notification::make()
            ->title('All messages expanded')
            ->success()
            ->send();
    }

    /**
     * Collapse all messages
     */
    public function collapseAll()
    {
        $this->expandedMessageIds = [];

        Notification::make()
            ->title('All messages collapsed')
            ->success()
            ->send();
    }

    /**
     * Delete email
     */
    public function deleteEmail(string $messageId)
    {
        // TODO: Implement with Gmail API
        Notification::make()
            ->title('Feature Coming Soon')
            ->body('Email deletion will be implemented with Gmail API integration')
            ->warning()
            ->send();
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

            Action::make('expand_all')
                ->label('Expand All')
                ->icon('heroicon-o-arrows-pointing-out')
                ->color('info')
                ->action(fn () => $this->expandAll()),

            Action::make('collapse_all')
                ->label('Collapse All')
                ->icon('heroicon-o-arrows-pointing-in')
                ->color('gray')
                ->action(fn () => $this->collapseAll()),

            Action::make('sync_clients')
                ->label('Sync with CRM')
                ->color('success')
                ->action(fn () => $this->syncClientsFromEmails()),
        ];
    }

    private function loadDemoMessages()
    {
        $this->messages = [
            [
                'id'               => 'demo_1',
                'from'             => 'john@bigcorp.com',
                'from_name'        => 'John Smith',
                'subject'          => 'Website redesign project - final review needed',
                'snippet'          => 'Hi Jordan, the website looks amazing! Just need to review the final changes before we go live. Can we schedule a call this week?',
                'date'             => now()->subHours(2)->toISOString(),
                'isRead'           => false,
                'isImportant'      => true,
                'isStarred'        => false,
                'labels'           => ['INBOX', 'IMPORTANT'],
                'body_text'        => 'Hi Jordan, the website looks amazing! Just need to review the final changes before we go live. Can we schedule a call this week?',
                'isClient'         => true,
                'category'         => 'client_communication',
                'urgency'          => 'high',
                'contains_invoice' => false,
                'contains_payment' => false,
                'clientInfo'       => [
                    'name'         => 'BigCorp Inc',
                    'status'       => 'active',
                    'projectValue' => 15000,
                    'lastInvoice'  => 'Paid',
                ]
            ],
            [
                'id'               => 'demo_2',
                'from'             => 'sarah@startup.io',
                'from_name'        => 'Sarah Johnson',
                'subject'          => 'Interested in web development services',
                'snippet'          => 'We\'re a growing startup looking for a reliable web developer. Found your portfolio online and love your work.',
                'date'             => now()->subHours(5)->toISOString(),
                'isRead'           => false,
                'isImportant'      => false,
                'isStarred'        => true,
                'labels'           => ['INBOX', 'STARRED'],
                'body_text'        => 'We\'re a growing startup looking for a reliable web developer. Found your portfolio online and love your work. Would love to discuss a potential project.',
                'isClient'         => false,
                'category'         => 'prospect_inquiry',
                'urgency'          => 'medium',
                'contains_invoice' => false,
                'contains_payment' => false,
                'prospectValue'    => 8000,
            ],
            [
                'id'               => 'demo_3',
                'from'             => 'mike@techsolutions.com',
                'from_name'        => 'Mike Davis',
                'subject'          => 'Invoice payment question',
                'snippet'          => 'Quick question about invoice #1234. The payment terms show NET 30 but our accounting wants to confirm the due date.',
                'date'             => now()->subDays(1)->toISOString(),
                'isRead'           => true,
                'isImportant'      => false,
                'isStarred'        => false,
                'labels'           => ['INBOX'],
                'body_text'        => 'Quick question about invoice #1234. The payment terms show NET 30 but our accounting wants to confirm the due date.',
                'isClient'         => true,
                'category'         => 'payment_inquiry',
                'urgency'          => 'medium',
                'contains_invoice' => true,
                'contains_payment' => true,
                'clientInfo'       => [
                    'name'         => 'Tech Solutions',
                    'status'       => 'payment_due',
                    'projectValue' => 5000,
                    'lastInvoice'  => '30 days overdue',
                ]
            ],
        ];
    }

    /**
     * Enhance email with intelligent client matching and categorization
     */
    private function enrichEmailWithClientData(array $email): array
    {
        // Smart client matching
        $clientMatch = $this->findClientMatch($email['from']);
        $email['isClient'] = !is_null($clientMatch);
        $email['clientInfo'] = $clientMatch;

        // Intelligent categorization
        $email['category'] = $this->categorizeEmail($email);
        $email['urgency'] = $this->analyzeUrgency($email);
        $email['contains_invoice'] = $this->detectInvoice($email);
        $email['contains_payment'] = $this->detectPayment($email);
        $email['project_context'] = $this->findProjectContext($email);

        return $email;
    }

    /**
     * Find matching client by email
     */
    private function findClientMatch(string $email): ?array
    {
        $normalizedEmail = strtolower(trim($email));

        if (isset($this->clients[$normalizedEmail])) {
            $client = $this->clients[$normalizedEmail];

            // Enhance with additional context
            return [
                'id'           => $client['id'],
                'name'         => $client['name'] ?? $client['company'] ?? 'Unknown Client',
                'company'      => $client['company'],
                'status'       => $client['status']->value ?? 'unknown',
                'website'      => $client['website'],
                'projectValue' => rand(5000, 50000), // TODO: Calculate from actual data
                'lastInvoice'  => $this->getLastInvoiceStatus($client['id']),
                'totalEmails'  => $this->getClientEmailCount($client['id']),
                'lastContact'  => $this->getLastContactDate($client['id']),
            ];
        }

        // Check for domain matches (for company emails)
        $domain = Str::after($normalizedEmail, '@');
        foreach ($this->clients as $clientEmail => $client) {
            if (Str::contains($clientEmail, $domain) || Str::contains($client['website'] ?? '', $domain)) {
                return [
                    'id'           => $client['id'],
                    'name'         => $client['company'] ?? $client['name'] ?? 'Unknown Client',
                    'company'      => $client['company'],
                    'status'       => $client['status']->value ?? 'unknown',
                    'website'      => $client['website'],
                    'projectValue' => rand(5000, 50000),
                    'lastInvoice'  => $this->getLastInvoiceStatus($client['id']),
                    'totalEmails'  => $this->getClientEmailCount($client['id']),
                    'lastContact'  => $this->getLastContactDate($client['id']),
                    'domainMatch'  => true,
                ];
            }
        }

        return null;
    }

    /**
     * Intelligent email categorization
     */
    private function categorizeEmail(array $email): string
    {
        $subject = strtolower($email['subject'] ?? '');
        $snippet = strtolower($email['snippet'] ?? '');
        $content = $subject . ' ' . $snippet;

        // Client communication patterns
        if ($email['isClient']) {
            if (Str::contains($content, ['invoice', 'payment', 'bill', 'due'])) {
                return 'payment_inquiry';
            }
            if (Str::contains($content, ['project', 'website', 'development', 'review'])) {
                return 'project_communication';
            }
            if (Str::contains($content, ['urgent', 'asap', 'immediately', 'emergency'])) {
                return 'urgent_client';
            }
            return 'client_communication';
        }

        // Prospect identification
        if (Str::contains($content, ['interested', 'quote', 'proposal', 'hire', 'services', 'portfolio'])) {
            return 'prospect_inquiry';
        }

        // Newsletter/marketing
        if (Str::contains($content, ['newsletter', 'unsubscribe', 'marketing', 'promotion'])) {
            return 'newsletter';
        }

        // GitHub/development
        if (Str::contains($content, ['github', 'pull request', 'commit', 'repository', 'code'])) {
            return 'development';
        }

        return 'personal';
    }

    /**
     * Analyze email urgency
     */
    private function analyzeUrgency(array $email): string
    {
        $content = strtolower(($email['subject'] ?? '') . ' ' . ($email['snippet'] ?? ''));

        if (Str::contains($content, ['urgent', 'asap', 'immediately', 'emergency', 'critical', 'help'])) {
            return 'high';
        }

        if ($email['isImportant'] || Str::contains($content, ['important', 'deadline', 'due', 'payment'])) {
            return 'medium';
        }

        return 'normal';
    }

    /**
     * Detect invoice-related content
     */
    private function detectInvoice(array $email): bool
    {
        $content = strtolower(($email['subject'] ?? '') . ' ' . ($email['snippet'] ?? ''));
        return Str::contains($content, ['invoice', 'bill', '#inv', 'payment due', 'amount due']);
    }

    /**
     * Detect payment-related content
     */
    private function detectPayment(array $email): bool
    {
        $content = strtolower(($email['subject'] ?? '') . ' ' . ($email['snippet'] ?? ''));
        return Str::contains($content, ['payment', 'paid', 'receipt', 'transaction', 'refund', 'paypal', 'stripe']);
    }

    /**
     * Find project context
     */
    private function findProjectContext(array $email): ?array
    {
        $content = strtolower(($email['subject'] ?? '') . ' ' . ($email['snippet'] ?? ''));

        foreach ($this->projects as $project) {
            $projectTerms = strtolower($project['title']);
            if (Str::contains($content, explode(' ', $projectTerms))) {
                return [
                    'id'         => $project['id'],
                    'title'      => $project['title'],
                    'slug'       => $project['slug'],
                    'created_at' => $project['created_at'],
                ];
            }
        }

        return null;
    }

    /**
     * Get last invoice status for client
     */
    private function getLastInvoiceStatus(int $clientId): string
    {
        // TODO: Implement with actual invoice system
        $statuses = ['Paid', 'Pending', '30 days overdue', 'Due in 15 days', 'Processing'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * Get client email count
     */
    private function getClientEmailCount(int $clientId): int
    {
        return ClientEmail::where('client_id', $clientId)->count();
    }

    /**
     * Get last contact date
     */
    private function getLastContactDate(int $clientId): ?string
    {
        $lastEmail = ClientEmail::where('client_id', $clientId)
            ->latest('email_date')
            ->first();

        return $lastEmail ? $lastEmail->email_date->diffForHumans() : null;
    }
}
