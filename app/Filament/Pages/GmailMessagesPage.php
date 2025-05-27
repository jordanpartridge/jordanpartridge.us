<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\ClientEmail;
use App\Models\Post;
use App\Models\GithubRepository;
use App\Models\Ride;
use Carbon\Carbon;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
    public $selectedLabels = ['INBOX']; // Support multiple labels
    public $availableLabels = [];
    public $systemLabels = [];
    public $userLabels = [];
    public $clients = [];
    public $projects = [];
    public $recentActivity = [];

    // Multi-account support
    public $selectedGmailAccount = null;
    public $availableGmailAccounts = [];

    // UI State
    public $showLabelsPanel = true;

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
        $this->loadGmailAccounts();
        $this->loadLabels();
        $this->loadClients();
        $this->loadProjects();
        $this->loadRecentActivity();

        // Initialize form data
        $this->form->fill([
            'searchTerm' => $this->searchTerm,
        ]);

        $this->loadMessages();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('searchTerm')
                    ->label('Search')
                    ->placeholder('Search emails... (try: from:sender@domain.com, subject:meeting, has:attachment)')
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->searchTerm = $state;
                        $this->loadMessages();
                    })
                    ->helperText('Advanced search: from:email, to:email, subject:text, has:attachment, is:unread, before:2023/12/01'),
            ])
            ->statePath('data');
    }

    /**
     * Load available Gmail accounts for account switching
     */
    public function loadGmailAccounts()
    {
        $user = auth()->user();

        $this->availableGmailAccounts = $user->gmailAccounts()
            ->active()
            ->get()
            ->map(function ($account) {
                return [
                    'id'           => $account->id,
                    'gmail_email'  => $account->gmail_email,
                    'display_name' => $account->display_name,
                    'is_primary'   => $account->is_primary,
                ];
            })
            ->toArray();

        // Set selected account to primary if none selected
        if (!$this->selectedGmailAccount && !empty($this->availableGmailAccounts)) {
            $primary = collect($this->availableGmailAccounts)->firstWhere('is_primary', true);
            $this->selectedGmailAccount = $primary ? $primary['gmail_email'] : $this->availableGmailAccounts[0]['gmail_email'];
        }
    }

    /**
     * Switch to a different Gmail account
     */
    public function switchGmailAccount(string $gmailEmail)
    {
        $this->selectedGmailAccount = $gmailEmail;

        // Reload everything for the new account
        $this->loadLabels();
        $this->loadMessages();

        $accountName = collect($this->availableGmailAccounts)->firstWhere('gmail_email', $gmailEmail)['display_name'] ?? $gmailEmail;

        Notification::make()
            ->title('Account switched')
            ->body("Now viewing emails from: {$accountName}")
            ->success()
            ->send();
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
            // Get the Gmail client for the selected account
            $gmailClient = $this->getCurrentGmailClient();

            // If only one label is selected, use it directly
            // If multiple labels are selected, we need to query each one and merge results
            if (count($this->selectedLabels) === 1) {
                $queryParams = [
                    'maxResults' => $maxResults,
                    'labelIds'   => $this->selectedLabels
                ];

                // Add search query if provided
                if (!empty($this->searchTerm)) {
                    $queryParams['q'] = $this->searchTerm;
                }

                Log::info('Gmail API Query (single label)', [
                    'selectedAccount' => $this->selectedGmailAccount,
                    'selectedLabels'  => $this->selectedLabels,
                    'queryParams'     => $queryParams
                ]);

                $rawMessages = $gmailClient->listMessages($queryParams);
            } else {
                // For multiple labels, we need to fetch from each label and merge
                // Since Gmail API's labelIds parameter uses AND logic (must have ALL labels)
                // we'll query each label separately and combine results
                $allMessages = collect();

                foreach ($this->selectedLabels as $labelId) {
                    $queryParams = [
                        'maxResults' => $maxResults * 2, // Get more from each to have options
                        'labelIds'   => [$labelId]
                    ];

                    if (!empty($this->searchTerm)) {
                        $queryParams['q'] = $this->searchTerm;
                    }

                    try {
                        $labelMessages = $gmailClient->listMessages($queryParams);
                        $allMessages = $allMessages->merge($labelMessages);
                    } catch (\Exception $e) {
                        Log::warning("Failed to fetch messages for label {$labelId}: " . $e->getMessage());
                    }
                }

                // Remove duplicates by message ID and take the most recent messages
                $rawMessages = $allMessages->unique('id')->sortByDesc('internalDate')->take($maxResults * 2);

                Log::info('Gmail API Query (multiple labels)', [
                    'selectedAccount' => $this->selectedGmailAccount,
                    'selectedLabels'  => $this->selectedLabels,
                    'totalMessages'   => $allMessages->count(),
                    'uniqueMessages'  => $rawMessages->count()
                ]);
            }

            // Convert Gmail Email objects to simple arrays for Livewire
            $this->messages = $rawMessages->map(function ($email) {
                // Ensure we have valid labelIds array
                $labelIds = is_array($email->labelIds ?? null) ? $email->labelIds : [];

                return [
                    'id'          => $email->id ?? '',
                    'from'        => html_entity_decode($email->from ?? 'Unknown', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'from_name'   => $email->from ? (strpos($email->from, '<') !== false ? html_entity_decode(substr($email->from, 0, strpos($email->from, '<')), ENT_QUOTES | ENT_HTML5, 'UTF-8') : '') : '',
                    'subject'     => html_entity_decode($email->subject ?? 'No Subject', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'snippet'     => html_entity_decode($email->snippet ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'date'        => $email->internalDate ? $email->internalDate->toISOString() : now()->toISOString(),
                    'isRead'      => !in_array('UNREAD', $labelIds),
                    'isImportant' => in_array('IMPORTANT', $labelIds),
                    'isStarred'   => in_array('STARRED', $labelIds), // Ensure this matches labels array
                    'labels'      => $labelIds, // Ensure this is always an array
                    'body_text'   => html_entity_decode($email->body->text ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'body_html'   => $email->body->html ?? '', // HTML body should already be properly encoded
                    // AI analysis will be added here
                    'isClient'         => false, // Will be determined by AI
                    'category'         => 'unknown', // Will be categorized by AI
                    'urgency'          => 'normal', // Will be analyzed by AI
                    'contains_invoice' => false, // Will be detected by AI
                    'contains_payment' => false, // Will be detected by AI
                ];
            })->filter(function ($message) {
                // Only include messages with valid IDs
                return !empty($message['id']);
            })->take($maxResults)->toArray();

            Log::info('Final message count', [
                'selectedAccount' => $this->selectedGmailAccount,
                'selectedLabels'  => $this->selectedLabels,
                'messageCount'    => count($this->messages),
                'messageIds'      => collect($this->messages)->pluck('id')->take(5)->toArray(),
                'messageFroms'    => collect($this->messages)->pluck('from')->take(5)->toArray()
            ]);

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
            // Get the Gmail client for the selected account
            $gmailClient = $this->getCurrentGmailClient();
            $gmailLabels = $gmailClient->listLabels();

            $this->availableLabels = $gmailLabels->map(function ($label) {
                if (is_array($label)) {
                    return [
                        'id'             => $label['id'] ?? '',
                        'name'           => $label['name'] ?? '',
                        'type'           => $label['type'] ?? 'user',
                        'messagesTotal'  => $label['messagesTotal'] ?? 0,
                        'messagesUnread' => $label['messagesUnread'] ?? 0,
                        'color'          => $this->getLabelColor($label['name'] ?? '', $label['type'] ?? 'user'),
                    ];
                }

                return [
                    'id'             => $label->id,
                    'name'           => $label->name,
                    'type'           => $label->type ?? 'user',
                    'messagesTotal'  => $label->messagesTotal ?? 0,
                    'messagesUnread' => $label->messagesUnread ?? 0,
                    'color'          => $this->getLabelColor($label->name, $label->type ?? 'user'),
                ];
            })->toArray();

            // Organize labels for sidebar display
            $this->systemLabels = collect($this->availableLabels)->where('type', 'system')->values()->toArray();
            $this->userLabels = collect($this->availableLabels)->where('type', 'user')->values()->toArray();

        } catch (\Exception $e) {
            // Fail silently for labels, don't break the page
            $this->availableLabels = [];
            $this->systemLabels = [];
            $this->userLabels = [];
        }
    }

    /**
     * Toggle label selection for filtering
     */
    public function toggleLabel(string $labelId)
    {
        $wasSelected = in_array($labelId, $this->selectedLabels);

        if ($wasSelected) {
            // Remove label from selection
            $this->selectedLabels = array_values(array_diff($this->selectedLabels, [$labelId]));
        } else {
            // Add label to selection
            $this->selectedLabels[] = $labelId;
        }

        // Ensure we always have at least one label selected
        if (empty($this->selectedLabels)) {
            $this->selectedLabels = ['INBOX'];
        }

        Log::info('Label toggled', [
            'labelId'        => $labelId,
            'wasSelected'    => $wasSelected,
            'selectedLabels' => $this->selectedLabels
        ]);

        // Reload messages with new label filter
        $this->loadMessages();

        $labelName = collect($this->availableLabels)->firstWhere('id', $labelId)['name'] ?? $labelId;
        $action = in_array($labelId, $this->selectedLabels) ? 'added' : 'removed';

        Notification::make()
            ->title('Filter updated')
            ->body("Label '{$labelName}' {$action}. Showing " . count($this->messages) . " messages.")
            ->success()
            ->send();
    }

    /**
     * Clear all label selections and reset to INBOX
     */
    public function clearLabelFilters()
    {
        $this->selectedLabels = ['INBOX'];
        $this->loadMessages();

        Notification::make()
            ->title('Filters cleared')
            ->body('Reset to Inbox view')
            ->success()
            ->send();
    }

    /**
     * Select only one label (exclusive selection)
     */
    public function selectOnlyLabel(string $labelId)
    {
        $this->selectedLabels = [$labelId];

        Log::info('Single label selected', [
            'labelId'        => $labelId,
            'selectedLabels' => $this->selectedLabels
        ]);

        $this->loadMessages();

        $labelName = collect($this->availableLabels)->firstWhere('id', $labelId)['name'] ?? $labelId;

        $messageCount = is_array($this->messages) ? count($this->messages) : 0;

        Notification::make()
            ->title('Filter changed')
            ->body("Now showing only '{$labelName}' - {$messageCount} messages found")
            ->success()
            ->send();
    }

    /**
     * Quick filter to starred emails only
     */
    public function showStarredOnly()
    {
        $this->selectOnlyLabel('STARRED');
    }

    /**
     * Quick filter to important emails only
     */
    public function showImportantOnly()
    {
        $this->selectOnlyLabel('IMPORTANT');
    }

    /**
     * Quick filter to unread emails only
     */
    public function showUnreadOnly()
    {
        $this->selectOnlyLabel('UNREAD');
    }

    /**
     * Toggle labels panel visibility
     */
    public function toggleLabelsPanel()
    {
        $this->showLabelsPanel = !$this->showLabelsPanel;
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
        // Validate input
        if (empty($messageId) || !is_string($messageId)) {
            Log::warning('Invalid messageId provided to toggleStar', ['messageId' => $messageId]);
            Notification::make()
                ->title('Error')
                ->body('Invalid message ID provided')
                ->danger()
                ->send();
            return;
        }

        $user = auth()->user();

        if (!$user || !$user->hasValidGmailToken()) {
            Notification::make()
                ->title('Not authenticated')
                ->body('Please authenticate with Gmail first.')
                ->danger()
                ->send();
            return;
        }

        // Initialize variables for rollback
        $originalMessage = null;
        $messageIndex = null;

        try {
            // Find the message in our current list
            $currentMessage = null;
            foreach ($this->messages as $index => $message) {
                if ($message['id'] === $messageId) {
                    $currentMessage = $message;
                    $messageIndex = $index;
                    $originalMessage = $message; // Store original for rollback
                    break;
                }
            }

            if (!$currentMessage) {
                Log::warning('Message not found in current list', ['messageId' => $messageId]);
                Notification::make()
                    ->title('Error')
                    ->body('Message not found in current view')
                    ->warning()
                    ->send();
                return;
            }

            // Validate message structure
            if (!$this->isValidMessage($currentMessage)) {
                Log::error('Invalid message structure for star toggle', [
                    'messageId' => $messageId,
                    'message'   => $currentMessage
                ]);
                Notification::make()
                    ->title('Error')
                    ->body('Invalid message data. Please refresh and try again.')
                    ->danger()
                    ->send();
                return;
            }

            // Use helper method to safely determine star status
            $isStarred = $this->getMessageStarStatus($currentMessage);

            Log::info('Toggling star for message', [
                'messageId'        => $messageId,
                'currentlyStarred' => $isStarred,
                'labels'           => $currentMessage['labels'] ?? []
            ]);

            $gmailClient = $this->getCurrentGmailClient();

            // Verify Gmail client is available
            if (!$gmailClient) {
                throw new Exception('Gmail client not available');
            }

            // Perform the API operation using modifyMessageLabels
            if ($isStarred) {
                // Remove star
                $response = $gmailClient->modifyMessageLabels($messageId, [], ['STARRED']);
                $action = 'removed star from';
                $newStarStatus = false;
            } else {
                // Add star
                $response = $gmailClient->modifyMessageLabels($messageId, ['STARRED'], []);
                $action = 'starred';
                $newStarStatus = true;
            }

            // Update the message in our current list immediately (optimistic update)
            if ($messageIndex !== null && isset($this->messages[$messageIndex])) {
                // Ensure labels is an array
                if (!is_array($this->messages[$messageIndex]['labels'])) {
                    $this->messages[$messageIndex]['labels'] = [];
                }

                if ($newStarStatus) {
                    // Add STARRED label if not present
                    if (!in_array('STARRED', $this->messages[$messageIndex]['labels'])) {
                        $this->messages[$messageIndex]['labels'][] = 'STARRED';
                    }
                } else {
                    // Remove STARRED label
                    $this->messages[$messageIndex]['labels'] = array_values(
                        array_filter($this->messages[$messageIndex]['labels'], function ($label) {
                            return $label !== 'STARRED';
                        })
                    );
                }

                $this->messages[$messageIndex]['isStarred'] = $newStarStatus;
            }

            Notification::make()
                ->title('Success')
                ->body("Successfully {$action} email")
                ->success()
                ->send();

            Log::info('Star toggle successful', [
                'messageId'     => $messageId,
                'action'        => $action,
                'newStarStatus' => $newStarStatus
            ]);

        } catch (\Exception $e) {
            // Rollback optimistic update if we had one
            if ($originalMessage && $messageIndex !== null && isset($this->messages[$messageIndex])) {
                $this->messages[$messageIndex] = $originalMessage;
            }

            Log::error('Star toggle error', [
                'messageId' => $messageId,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            // Provide more specific error messages
            $errorMessage = 'Failed to update star status';
            if (str_contains($e->getMessage(), 'authentication') || str_contains($e->getMessage(), 'token')) {
                $errorMessage = 'Gmail authentication expired. Please re-authenticate.';
            } elseif (str_contains($e->getMessage(), 'not found')) {
                $errorMessage = 'Email not found. It may have been deleted or moved.';
            } elseif (str_contains($e->getMessage(), 'rate limit') || str_contains($e->getMessage(), 'quota')) {
                $errorMessage = 'Gmail API rate limit exceeded. Please try again later.';
            } elseif (str_contains($e->getMessage(), 'network') || str_contains($e->getMessage(), 'connection')) {
                $errorMessage = 'Network error. Please check your connection and try again.';
            }

            Notification::make()
                ->title('Error')
                ->body($errorMessage)
                ->danger()
                ->send();

            // If it's an auth error, suggest re-authentication
            if (str_contains($e->getMessage(), 'authentication') || str_contains($e->getMessage(), 'token')) {
                Notification::make()
                    ->title('Re-authentication needed')
                    ->body('Click here to re-authenticate with Gmail')
                    ->warning()
                    ->actions([
                        NotificationAction::make('authenticate')
                            ->button()
                            ->url(GmailIntegrationPage::getUrl())
                    ])
                    ->send();
            }
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
            $gmailClient = $this->getCurrentGmailClient();
            $email = $gmailClient->getMessage($messageId);

            // Extract email body content - Enhanced HTML extraction
            $bodyHtml = '';
            $bodyText = $email->body ?? '';

            // Parse Gmail API payload for HTML content
            $payload = $email->payload ?? [];
            $this->extractEmailBodies($payload, $bodyHtml, $bodyText);

            // Prepare raw email data for processing
            $rawEmailData = [
                'subject'         => $email->subject ?? 'No Subject',
                'from'            => $email->from ?? 'Unknown Sender',
                'date'            => $email->internalDate ? $email->internalDate->format('M j, Y g:i A') : 'Unknown Date',
                'body_html'       => $bodyHtml,
                'body_text'       => $bodyText,
                'snippet'         => $email->snippet ?? '',
                'has_attachments' => !empty($email->attachments ?? []),
                'attachments'     => $email->attachments ?? [],
            ];

            // Use EmailContentService to process and sanitize content
            $contentService = app(\App\Services\Gmail\EmailContentService::class);

            try {
                $processedContent = $contentService->processEmailContent($rawEmailData);
            } catch (\Exception $sanitizationError) {
                Log::warning('Email sanitization failed, using fallback', [
                    'message_id' => $messageId,
                    'error'      => $sanitizationError->getMessage()
                ]);

                // Fallback: use basic sanitization
                $processedContent = [
                    'subject'         => $rawEmailData['subject'],
                    'from'            => $rawEmailData['from'],
                    'date'            => $rawEmailData['date'],
                    'snippet'         => $rawEmailData['snippet'],
                    'body_html'       => !empty($bodyHtml) ? strip_tags($bodyHtml, '<p><br><strong><em><u><ol><ul><li><a><h1><h2><h3><h4><h5><h6><table><tr><td><th><div><span>') : '',
                    'body_text'       => $bodyText,
                    'has_attachments' => $rawEmailData['has_attachments'],
                    'attachments'     => $rawEmailData['attachments'],
                ];
            }

            $this->emailPreview = array_merge($processedContent, [
                'id'        => $email->id,
                'labels'    => $email->labelIds ?? [],
                'isStarred' => in_array('STARRED', $email->labelIds ?? []),
                'isRead'    => !in_array('UNREAD', $email->labelIds ?? []),
            ]);

            $this->showingEmailId = $messageId;

        } catch (\Exception $e) {
            Log::error('Email preview failed', [
                'message_id' => $messageId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine()
            ]);

            Notification::make()
                ->title('Email Preview Failed')
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
            $gmailClient = $this->getCurrentGmailClient();
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
     * Mark email as read
     */
    public function markAsRead(string $messageId)
    {
        if (empty($messageId) || !is_string($messageId)) {
            Log::warning('Invalid messageId provided to markAsRead', ['messageId' => $messageId]);
            Notification::make()
                ->title('Error')
                ->body('Invalid message ID provided')
                ->danger()
                ->send();
            return;
        }

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
            $gmailClient = $this->getCurrentGmailClient();

            // Use modifyMessageLabels which is more reliable for label operations
            $gmailClient->modifyMessageLabels($messageId, [], ['UNREAD']);

            // Update the local message state
            $this->updateMessageInList($messageId, ['isUnread' => false]);

            Notification::make()
                ->title('Message marked as read')
                ->success()
                ->send();

            Log::info('Email marked as read', [
                'message_id' => $messageId,
                'user_id'    => $user->id
            ]);

        } catch (\Exception $e) {
            $this->handleEmailActionError($e, 'marking email as read');
        }
    }

    /**
     * Mark email as unread
     */
    public function markAsUnread(string $messageId)
    {
        if (empty($messageId) || !is_string($messageId)) {
            Log::warning('Invalid messageId provided to markAsUnread', ['messageId' => $messageId]);
            Notification::make()
                ->title('Error')
                ->body('Invalid message ID provided')
                ->danger()
                ->send();
            return;
        }

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
            $gmailClient = $this->getCurrentGmailClient();

            // Use modifyMessageLabels which is more reliable for label operations
            $gmailClient->modifyMessageLabels($messageId, ['UNREAD'], []);

            // Update the local message state
            $this->updateMessageInList($messageId, ['isUnread' => true]);

            Notification::make()
                ->title('Message marked as unread')
                ->success()
                ->send();

            Log::info('Email marked as unread', [
                'message_id' => $messageId,
                'user_id'    => $user->id
            ]);

        } catch (\Exception $e) {
            $this->handleEmailActionError($e, 'marking email as unread');
        }
    }

    /**
     * Archive email (remove from INBOX)
     */
    public function archiveEmail(string $messageId)
    {
        if (empty($messageId) || !is_string($messageId)) {
            Log::warning('Invalid messageId provided to archiveEmail', ['messageId' => $messageId]);
            Notification::make()
                ->title('Error')
                ->body('Invalid message ID provided')
                ->danger()
                ->send();
            return;
        }

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
            $gmailClient = $this->getCurrentGmailClient();

            // Use modifyMessageLabels to remove INBOX label (archive)
            $gmailClient->modifyMessageLabels($messageId, [], ['INBOX']);

            // Remove from local message list if we're viewing INBOX
            if (in_array('INBOX', $this->selectedLabels)) {
                $this->removeMessageFromList($messageId);
            }

            Notification::make()
                ->title('Message archived')
                ->success()
                ->send();

            Log::info('Email archived', [
                'message_id' => $messageId,
                'user_id'    => $user->id
            ]);

        } catch (\Exception $e) {
            $this->handleEmailActionError($e, 'archiving email');
        }
    }

    /**
     * Delete email (move to trash)
     */
    public function deleteEmail(string $messageId)
    {
        if (empty($messageId) || !is_string($messageId)) {
            Log::warning('Invalid messageId provided to deleteEmail', ['messageId' => $messageId]);
            Notification::make()
                ->title('Error')
                ->body('Invalid message ID provided')
                ->danger()
                ->send();
            return;
        }

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
            $gmailClient = $this->getCurrentGmailClient();

            // Use modifyMessageLabels to add TRASH label (soft delete)
            $gmailClient->modifyMessageLabels($messageId, ['TRASH'], []);

            // Remove from local message list
            $this->removeMessageFromList($messageId);

            Notification::make()
                ->title('Message moved to trash')
                ->success()
                ->send();

            Log::info('Email deleted (moved to trash)', [
                'message_id' => $messageId,
                'user_id'    => $user->id
            ]);

        } catch (\Exception $e) {
            $this->handleEmailActionError($e, 'deleting email');
        }
    }

    /**
     * Quick search presets for common queries
     */
    public function quickSearch(string $preset): void
    {
        $searches = [
            'unread'      => 'is:unread',
            'today'       => 'newer_than:1d',
            'attachments' => 'has:attachment',
            'important'   => 'is:important',
            'starred'     => 'is:starred',
            'clients'     => 'from:client OR to:client', // Basic client detection
        ];

        if (isset($searches[$preset])) {
            $this->searchTerm = $searches[$preset];
            $this->data['searchTerm'] = $this->searchTerm;
            $this->loadMessages();

            Notification::make()
                ->title('Quick search applied')
                ->body("Searching for: {$searches[$preset]}")
                ->success()
                ->send();
        }
    }

    /**
     * Clear search and show all messages
     */
    public function clearSearch(): void
    {
        $this->searchTerm = '';
        $this->data['searchTerm'] = '';
        $this->loadMessages();

        Notification::make()
            ->title('Search cleared')
            ->success()
            ->send();
    }

    /**
     * Create a contact from an email message
     */
    public function createContactFromEmail(string $messageId)
    {
        $message = collect($this->messages)->firstWhere('id', $messageId);

        if (!$message) {
            Notification::make()
                ->title('Error')
                ->body('Message not found')
                ->danger()
                ->send();
            return;
        }

        try {
            $email = $message['from'];
            $name = $message['from_name'] ?: explode('@', $email)[0];

            // Check if contact already exists
            $existingClient = Client::where('email', $email)->first();
            if ($existingClient) {
                Notification::make()
                    ->title('Contact Exists')
                    ->body("Contact '{$name}' already exists in your CRM")
                    ->warning()
                    ->send();
                return;
            }

            // Create new client from email
            Client::create([
                'name'            => $name,
                'email'           => $email,
                'status'          => 'lead',
                'notes'           => 'Auto-created from Gmail: ' . $message['subject'],
                'user_id'         => auth()->id(),
                'last_contact_at' => Carbon::parse($message['date']),
            ]);

            // Update the message to mark as client
            foreach ($this->messages as &$msg) {
                if ($msg['id'] === $messageId) {
                    $msg['isClient'] = true;
                    break;
                }
            }

            Notification::make()
                ->title('Contact Created')
                ->body("Successfully created contact '{$name}' in your CRM")
                ->success()
                ->send();

            $this->loadClients();

        } catch (\Exception $e) {
            Log::error('Failed to create contact from email', [
                'messageId' => $messageId,
                'error'     => $e->getMessage()
            ]);

            Notification::make()
                ->title('Error')
                ->body('Failed to create contact: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Get appropriate label for GitHub link based on URL and message content
     */
    public function getGitHubLinkLabel(string $url, array $message): string
    {
        $subject = strtolower($message['subject'] ?? '');

        if (str_contains($url, '/pull/')) {
            return 'View PR';
        }

        if (str_contains($url, '/issues/')) {
            return 'View Issue';
        }

        if (str_contains($url, '/actions') || str_contains($url, '/runs/')) {
            return 'View Action';
        }

        if (str_contains($subject, 'pull request') || str_contains($subject, 'pr ')) {
            return 'View PR';
        }

        if (str_contains($subject, 'issue')) {
            return 'View Issue';
        }

        if (str_contains($subject, 'action') || str_contains($subject, 'workflow') || str_contains($subject, 'build')) {
            return 'View Action';
        }

        return 'View on GitHub';
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

    /**
     * Extract both HTML and plain text content from Gmail API payload
     */
    private function extractEmailBodies(array $payload, &$bodyHtml, &$bodyText): void
    {
        Log::info('Email body extraction started', [
            'has_body_data' => isset($payload['body']['data']),
            'has_parts'     => isset($payload['parts']),
            'mime_type'     => $payload['mimeType'] ?? 'unknown',
            'parts_count'   => isset($payload['parts']) ? count($payload['parts']) : 0
        ]);

        // Handle single part messages
        if (isset($payload['body']['data'])) {
            $mimeType = $payload['mimeType'] ?? '';
            $content = base64_decode(strtr($payload['body']['data'], '-_', '+/'));

            Log::info('Single part message', [
                'mime_type'       => $mimeType,
                'content_length'  => strlen($content),
                'content_preview' => substr($content, 0, 100)
            ]);

            if ($mimeType === 'text/html') {
                $bodyHtml = $content;
            } elseif ($mimeType === 'text/plain') {
                $bodyText = $content;
            }
        }

        // Handle multi-part messages
        if (isset($payload['parts']) && is_array($payload['parts'])) {
            Log::info('Multi-part message processing', ['parts_count' => count($payload['parts'])]);

            foreach ($payload['parts'] as $index => $part) {
                $mimeType = $part['mimeType'] ?? '';

                Log::info("Processing part {$index}", [
                    'mime_type'          => $mimeType,
                    'has_body_data'      => isset($part['body']['data']),
                    'has_nested_parts'   => isset($part['parts']),
                    'nested_parts_count' => isset($part['parts']) ? count($part['parts']) : 0
                ]);

                if ($mimeType === 'text/html' && isset($part['body']['data'])) {
                    $content = base64_decode(strtr($part['body']['data'], '-_', '+/'));
                    $bodyHtml = $content;
                    Log::info('HTML content extracted', [
                        'content_length' => strlen($content),
                        'preview'        => substr($content, 0, 200)
                    ]);
                } elseif ($mimeType === 'text/plain' && isset($part['body']['data'])) {
                    $content = base64_decode(strtr($part['body']['data'], '-_', '+/'));
                    $bodyText = $content;
                    Log::info('Plain text content extracted', [
                        'content_length' => strlen($content),
                        'preview'        => substr($content, 0, 200)
                    ]);
                }

                // Handle nested multipart (like multipart/alternative)
                if (str_starts_with($mimeType, 'multipart/') && isset($part['parts'])) {
                    Log::info('Processing nested multipart', ['nested_mime_type' => $mimeType]);
                    $this->extractEmailBodies($part, $bodyHtml, $bodyText);
                }
            }
        }

        Log::info('Email body extraction completed', [
            'html_extracted' => !empty($bodyHtml),
            'text_extracted' => !empty($bodyText),
            'html_length'    => strlen($bodyHtml),
            'text_length'    => strlen($bodyText)
        ]);
    }

    /**
     * Get the current Gmail client for the selected account
     */
    private function getCurrentGmailClient()
    {
        $user = auth()->user();

        if (!$user->hasValidGmailToken()) {
            return null;
        }

        // Ensure we have a selected account
        if (!$this->selectedGmailAccount) {
            $this->loadGmailAccounts();
        }

        Log::info('Getting Gmail client for account', [
            'selectedAccount'   => $this->selectedGmailAccount,
            'availableAccounts' => collect($this->availableGmailAccounts)->pluck('gmail_email')->toArray()
        ]);

        return $this->selectedGmailAccount
            ? $user->getGmailClientForAccount($this->selectedGmailAccount)
            : $user->getGmailClient();
    }

    /**
     * Helper method to update a message in the local list
     */
    private function updateMessageInList(string $messageId, array $updates): void
    {
        $this->messages = collect($this->messages)->map(function ($message) use ($messageId, $updates) {
            if ($message['id'] === $messageId) {
                return array_merge($message, $updates);
            }
            return $message;
        })->toArray();
    }

    /**
     * Helper method to remove a message from the local list
     */
    private function removeMessageFromList(string $messageId): void
    {
        $this->messages = collect($this->messages)->filter(function ($message) use ($messageId) {
            return $message['id'] !== $messageId;
        })->values()->toArray();
    }

    /**
     * Centralized error handling for email actions
     */
    private function handleEmailActionError(\Exception $e, string $action): void
    {
        Log::error("Failed {$action}", [
            'error'   => $e->getMessage(),
            'user_id' => auth()->id(),
        ]);

        $errorMessage = 'Failed to perform action. Please try again.';

        if (str_contains($e->getMessage(), 'authentication') || str_contains($e->getMessage(), 'invalid_grant')) {
            $errorMessage = 'Gmail authentication expired. Please re-authenticate.';
        } elseif (str_contains($e->getMessage(), 'not found')) {
            $errorMessage = 'Email not found. It may have been deleted or moved.';
        } elseif (str_contains($e->getMessage(), 'rate limit') || str_contains($e->getMessage(), 'quota')) {
            $errorMessage = 'Gmail API rate limit exceeded. Please try again later.';
        } elseif (str_contains($e->getMessage(), 'network') || str_contains($e->getMessage(), 'connection')) {
            $errorMessage = 'Network error. Please check your connection and try again.';
        }

        Notification::make()
            ->title('Error')
            ->body($errorMessage)
            ->danger()
            ->send();
    }

    /**
     * Get color for label based on type and name
     */
    private function getLabelColor(string $name, string $type): string
    {
        if ($type === 'system') {
            return match (strtoupper($name)) {
                'INBOX'     => 'blue',
                'SENT'      => 'green',
                'DRAFT'     => 'yellow',
                'TRASH'     => 'gray',
                'SPAM'      => 'red',
                'STARRED'   => 'yellow',
                'IMPORTANT' => 'orange',
                default     => 'blue'
            };
        }

        // User labels get purple color
        return 'purple';
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

    /**
     * Safely validate a message structure to prevent star toggle errors
     */
    private function isValidMessage(array $message): bool
    {
        // Check required fields
        if (!isset($message['id']) || empty($message['id'])) {
            return false;
        }

        // Ensure labels is an array
        if (!isset($message['labels']) || !is_array($message['labels'])) {
            return false;
        }

        // Ensure isStarred is a boolean
        if (!isset($message['isStarred']) || !is_bool($message['isStarred'])) {
            return false;
        }

        return true;
    }

    /**
     * Safely get star status for a message
     */
    private function getMessageStarStatus(array $message): bool
    {
        if (!$this->isValidMessage($message)) {
            return false;
        }

        // Check both the labels array and isStarred field for consistency
        $hasStarredLabel = in_array('STARRED', $message['labels']);
        $isStarredField = $message['isStarred'] ?? false;

        // Log inconsistency for debugging
        if ($hasStarredLabel !== $isStarredField) {
            Log::warning('Star status inconsistency detected', [
                'messageId'       => $message['id'],
                'hasStarredLabel' => $hasStarredLabel,
                'isStarredField'  => $isStarredField,
                'labels'          => $message['labels']
            ]);
        }

        // Trust the labels array as source of truth
        return $hasStarredLabel;
    }
}
