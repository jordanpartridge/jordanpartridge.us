<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\ClientEmail;
use App\Models\Post;
use App\Models\GithubRepository;
use App\Models\Ride;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GmailMessagesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static string $view = 'filament.pages.gmail-messages-page';

    protected static ?string $navigationGroup = 'Email Management';

    protected static ?string $navigationLabel = 'Gmail Messages';

    protected static ?int $navigationSort = 91;

    // Remove from navigation menu, we'll navigate to it from the GmailIntegrationPage
    protected static bool $shouldRegisterNavigation = false;

    public $messages = [];
    public $filter = 'all';
    public $searchTerm = '';
    public $selectedCategory = 'all';
    public $clients = [];
    public $projects = [];
    public $recentActivity = [];

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
        $this->loadClients();
        $this->loadProjects();
        $this->loadRecentActivity();
        $this->loadMessages();
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

            // Get Gmail messages - real data now!
            $rawMessages = $gmailClient->listMessages(['maxResults' => $maxResults]);

            // Convert Gmail Email objects to simple arrays for Livewire
            $this->messages = $rawMessages->map(function ($email) {
                return [
                    'id'          => $email->id,
                    'from'        => $email->from->email ?? 'Unknown',
                    'from_name'   => $email->from->name ?? '',
                    'subject'     => $email->subject ?? 'No Subject',
                    'snippet'     => $email->snippet ?? '',
                    'date'        => $email->date ? $email->date->toISOString() : now()->toISOString(),
                    'isRead'      => !in_array('UNREAD', $email->labelIds ?? []),
                    'isImportant' => in_array('IMPORTANT', $email->labelIds ?? []),
                    'isStarred'   => in_array('STARRED', $email->labelIds ?? []),
                    'labels'      => $email->labelIds ?? [],
                    'body_text'   => $email->body->text ?? '',
                    'body_html'   => $email->body->html ?? '',
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
     * Toggle email star status
     */
    public function toggleStar(string $messageId)
    {
        // TODO: Implement with Gmail API
        Notification::make()
            ->title('Feature Coming Soon')
            ->body('Star toggle will be implemented with Gmail API integration')
            ->info()
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
