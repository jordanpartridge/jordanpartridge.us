<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            $user = auth()->user();
            if (!$user || !$user->hasValidGmailToken()) {
                return;
            }

            $gmailClient = $user->getGmailClient();
            if (!$gmailClient) {
                return;
            }

            $gmailClient->modifyMessageLabels($messageId, [], ['UNREAD']);
            $this->loadEmailData();
        } catch (\Exception $e) {
            // Handle error
        }
    }

    protected function loadEmailData(): void
    {
        try {
            // For now, use the first user since this is a personal dashboard
            $user = User::first();
            if (!$user || !$user->hasValidGmailToken()) {
                // No user or no valid token - set to demo data
                $this->priorityEmails = collect();
                $this->unreadCounts = collect(['inbox' => 0, 'important' => 0, 'action_required' => 0]);
                return;
            }

            $gmailClient = $user->getGmailClient();
            if (!$gmailClient) {
                $this->priorityEmails = collect();
                $this->unreadCounts = collect(['inbox' => 0, 'important' => 0, 'action_required' => 0]);
                return;
            }

            // Get priority emails (unread from important senders or with important labels)
            $priorityQuery = 'is:unread (is:important OR from:(*@important-client.com) OR label:urgent OR label:action-required)';
            $messages = $gmailClient->listMessages([
                'q'          => $priorityQuery,
                'maxResults' => 10,
            ]);

            $this->priorityEmails = collect($messages ?? [])
                ->take(5)
                ->map(function ($message) use ($gmailClient) {
                    // Handle both Email objects and array responses
                    $messageId = is_object($message) ? $message->id : $message['id'];
                    $details = $gmailClient->getMessage($messageId);

                    // Handle both object and array responses for details
                    if (is_object($details)) {
                        return [
                            'id'      => $details->id,
                            'subject' => Str::limit($details->subject ?? 'No Subject', 50),
                            'from'    => $this->extractSenderName($details->from ?? 'Unknown'),
                            'time'    => $this->formatEmailTime($details->internalDate ?? null),
                            'snippet' => Str::limit($details->snippet ?? '', 100),
                            'labels'  => $this->extractImportantLabels($details->labelIds ?? []),
                        ];
                    } else {
                        $headers = collect($details['payload']['headers'] ?? []);
                        return [
                            'id'      => $messageId,
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
                    }
                });

            // Get unread counts by category - simplified approach
            $this->unreadCounts = collect([
                'inbox'           => $this->getUnreadCount('is:unread in:inbox', $gmailClient),
                'important'       => $this->getUnreadCount('is:unread is:important', $gmailClient),
                'action_required' => $this->getUnreadCount('is:unread label:action-required', $gmailClient),
            ]);

            $this->totalUnread = $this->unreadCounts->sum();

        } catch (\Exception $e) {
            // Log the actual error so we can see what's failing
            Log::error('EmailPrioritiesWidget failed to load data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->priorityEmails = collect();
            $this->unreadCounts = collect(['inbox' => 0, 'important' => 0, 'action_required' => 0]);
        }
    }

    protected function getUnreadCount(string $query, $gmailClient = null): int
    {
        try {
            if (!$gmailClient) {
                return 0;
            }
            $result = $gmailClient->listMessages(['q' => $query, 'maxResults' => 1]);
            return $result['resultSizeEstimate'] ?? count($result ?? []);
        } catch (\Exception $e) {
            Log::debug('Gmail unread count query failed', ['query' => $query, 'error' => $e->getMessage()]);
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
