<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\GmailService;
use App\Models\Post;
use App\Models\GithubRepository;
use App\Models\Ride;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TodaysFocusWidget extends Widget
{
    protected static string $view = 'filament.widgets.todays-focus';

    protected static ?int $sort = 2;

    public Collection $priorities;
    public array $suggestions = [];

    protected int | string | array $columnSpan = [
        'default' => 1,
        'lg'      => 2,
    ];

    public function mount(): void
    {
        $this->priorities = collect();
        $this->extractPriorities();
        $this->generateSuggestions();
    }

    public function markComplete(string $type, string $id): void
    {
        // Handle marking items as complete
        // This would vary based on the type of priority
        $this->extractPriorities();
    }

    protected function extractPriorities(): void
    {
        $priorities = collect();

        // Extract priorities from emails
        $emailPriorities = $this->extractEmailPriorities();
        $priorities = $priorities->concat($emailPriorities);

        // Check for draft posts
        $draftPosts = Post::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get()
            ->map(fn ($post) => [
                'type'    => 'post',
                'title'   => "Finish blog post: {$post->title}",
                'context' => "Last edited " . $post->updated_at->diffForHumans(),
                'action'  => route('filament.admin.resources.posts.edit', $post),
                'icon'    => 'heroicon-o-document-text',
                'color'   => 'warning',
            ]);

        $priorities = $priorities->concat($draftPosts);

        // Check GitHub repos with recent activity
        $activeRepos = GithubRepository::where('updated_at', '>', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get()
            ->map(fn ($repo) => [
                'type'    => 'github',
                'title'   => "Review updates: {$repo->name}",
                'context' => $repo->open_issues_count . " open issues",
                'action'  => $repo->html_url,
                'icon'    => 'heroicon-o-code-bracket',
                'color'   => 'info',
            ]);

        $priorities = $priorities->concat($activeRepos);

        // Sort by importance and take top 5
        $this->priorities = $priorities->take(5);
    }

    protected function extractEmailPriorities(): Collection
    {
        try {
            $gmailService = app(GmailService::class);

            // Look for action items in emails
            $actionQuery = 'is:unread (subject:"action required" OR subject:"urgent" OR subject:"deadline" OR subject:"due today" OR subject:"by EOD")';
            $messages = $gmailService->listMessages([
                'q'          => $actionQuery,
                'maxResults' => 5,
            ]);

            return collect($messages['messages'] ?? [])
                ->take(3)
                ->map(function ($message) use ($gmailService) {
                    $details = $gmailService->getMessage($message['id']);
                    $headers = collect($details['payload']['headers'] ?? []);
                    $subject = $headers->firstWhere('name', 'Subject')['value'] ?? 'Email Task';

                    // Extract action from subject or content
                    $action = $this->extractActionFromEmail($subject, $details['snippet'] ?? '');

                    return [
                        'type'    => 'email',
                        'title'   => $action,
                        'context' => 'From: ' . $this->extractSenderName($headers->firstWhere('name', 'From')['value'] ?? ''),
                        'action'  => "https://mail.google.com/mail/u/0/#inbox/{$message['id']}",
                        'icon'    => 'heroicon-o-envelope',
                        'color'   => 'danger',
                    ];
                });
        } catch (\Exception $e) {
            return collect();
        }
    }

    protected function extractActionFromEmail(string $subject, string $snippet): string
    {
        // Look for action keywords and extract the main task
        $patterns = [
            '/please\s+(.+?)(?:\.|$)/i',
            '/need\s+(?:you\s+)?to\s+(.+?)(?:\.|$)/i',
            '/action\s*required:\s*(.+?)(?:\.|$)/i',
            '/urgent:\s*(.+?)(?:\.|$)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $subject . ' ' . $snippet, $matches)) {
                return ucfirst(trim($matches[1]));
            }
        }

        // Fallback to subject
        return Str::limit($subject, 60);
    }

    protected function extractSenderName(string $from): string
    {
        if (preg_match('/^(.+?)\s*</', $from, $matches)) {
            return trim($matches[1], '"');
        }
        return Str::limit($from, 30);
    }

    protected function generateSuggestions(): void
    {
        $suggestions = [];

        // Time-based suggestions
        $hour = now()->hour;
        $dayOfWeek = now()->dayOfWeek;

        // Morning routine
        if ($hour < 10) {
            if (!$this->hasRideToday()) {
                $suggestions[] = [
                    'text' => 'Morning ride? The weather looks good today.',
                    'icon' => 'heroicon-o-sun',
                ];
            }
        }

        // Midday check
        if ($hour >= 12 && $hour < 14) {
            $suggestions[] = [
                'text' => 'Good time to clear out emails before the afternoon.',
                'icon' => 'heroicon-o-inbox',
            ];
        }

        // End of day
        if ($hour >= 16) {
            $suggestions[] = [
                'text' => 'Review tomorrow\'s calendar and prep for meetings.',
                'icon' => 'heroicon-o-calendar',
            ];
        }

        // Weekly tasks
        if ($dayOfWeek === 1) { // Monday
            $suggestions[] = [
                'text' => 'Weekly planning: Set this week\'s goals.',
                'icon' => 'heroicon-o-clipboard-document-list',
            ];
        } elseif ($dayOfWeek === 5) { // Friday
            $suggestions[] = [
                'text' => 'Weekly review: Reflect on accomplishments.',
                'icon' => 'heroicon-o-chart-bar',
            ];
        }

        $this->suggestions = array_slice($suggestions, 0, 2);
    }

    protected function hasRideToday(): bool
    {
        return Ride::whereDate('date', today())
            ->exists();
    }
}
