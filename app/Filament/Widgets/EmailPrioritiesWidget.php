<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\GmailService;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EmailPrioritiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.email-priorities';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '5m';

    public ?Collection $priorityEmails = null;
    public ?Collection $unreadCounts = null;
    public int $totalUnread = 0;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm'      => 2,
        'lg'      => 1,
    ];

    public function mount(): void
    {
        $this->loadEmailData();
    }

    public function markAsRead(string $messageId): void
    {
        try {
            $gmailService = app(GmailService::class);
            $gmailService->modifyMessage($messageId, [], ['UNREAD']);
            $this->loadEmailData();
        } catch (\Exception $e) {
            // Handle error
        }
    }

    protected function loadEmailData(): void
    {
        try {
            $gmailService = app(GmailService::class);

            // Get priority emails (unread from important senders or with important labels)
            $priorityQuery = 'is:unread (is:important OR from:(*@important-client.com) OR label:urgent OR label:action-required)';
            $messages = $gmailService->listMessages([
                'q'          => $priorityQuery,
                'maxResults' => 10,
            ]);

            $this->priorityEmails = collect($messages['messages'] ?? [])
                ->take(5)
                ->map(function ($message) use ($gmailService) {
                    $details = $gmailService->getMessage($message['id']);
                    $headers = collect($details['payload']['headers'] ?? []);

                    return [
                        'id'      => $message['id'],
                        'subject' => Str::limit(
                            $headers->firstWhere('name', 'Subject')['value'] ?? 'No Subject',
                            50
                        ),
                        'from' => $this->extractSenderName(
                            $headers->firstWhere('name', 'From')['value'] ?? 'Unknown'
                        ),
                        'time'    => $this->formatEmailTime($details['internalDate'] ?? null),
                        'snippet' => Str::limit($details['snippet'] ?? '', 100),
                        'labels'  => $this->extractImportantLabels($details['labelIds'] ?? []),
                    ];
                });

            // Get unread counts by category
            $this->unreadCounts = collect([
                'inbox'           => $this->getUnreadCount('is:unread in:inbox'),
                'important'       => $this->getUnreadCount('is:unread is:important'),
                'action_required' => $this->getUnreadCount('is:unread label:action-required'),
            ]);

            $this->totalUnread = $this->unreadCounts->sum();

        } catch (\Exception $e) {
            $this->priorityEmails = collect();
            $this->unreadCounts = collect(['inbox' => 0, 'important' => 0, 'action_required' => 0]);
        }
    }

    protected function getUnreadCount(string $query): int
    {
        try {
            $gmailService = app(GmailService::class);
            $result = $gmailService->listMessages(['q' => $query, 'maxResults' => 1]);
            return $result['resultSizeEstimate'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function extractSenderName(string $from): string
    {
        if (preg_match('/^(.+?)\s*</', $from, $matches)) {
            return trim($matches[1], '"');
        }
        return Str::limit($from, 30);
    }

    protected function formatEmailTime($timestamp): string
    {
        if (!$timestamp) {
            return '';
        }

        $date = Carbon::createFromTimestampMs($timestamp);

        if ($date->isToday()) {
            return $date->format('g:i A');
        } elseif ($date->isYesterday()) {
            return 'Yesterday ' . $date->format('g:i A');
        } else {
            return $date->format('M j');
        }
    }

    protected function extractImportantLabels(array $labelIds): array
    {
        $importantLabels = [
            'IMPORTANT'         => 'Important',
            'CATEGORY_PERSONAL' => 'Personal',
            'CATEGORY_WORK'     => 'Work',
            'STARRED'           => 'Starred',
        ];

        return collect($labelIds)
            ->filter(fn ($id) => isset($importantLabels[$id]))
            ->map(fn ($id) => $importantLabels[$id])
            ->values()
            ->toArray();
    }
}
