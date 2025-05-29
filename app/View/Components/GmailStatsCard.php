<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;

class GmailStatsCard extends Component
{
    public ?array $stats = null;
    public bool $showDetails;
    public string $size;

    public function __construct(
        bool $showDetails = false,
        string $size = 'default',
        ?User $user = null
    ) {
        $this->showDetails = $showDetails;
        $this->size = $size;
        $this->stats = $this->loadGmailStats($user);
    }

    public function render()
    {
        return view('components.gmail-stats-card');
    }

    private function loadGmailStats(?User $user = null): ?array
    {
        try {
            // Use provided user or first user for personal dashboard
            $user = $user ?: User::first();

            if (!$user || !$user->hasValidGmailToken()) {
                return null;
            }

            $gmailClient = $user->getGmailClient();
            if (!$gmailClient) {
                return null;
            }

            // Get basic counts with simple queries
            return [
                'inbox'           => $this->getUnreadCount('is:unread in:inbox', $gmailClient),
                'important'       => $this->getUnreadCount('is:unread is:important', $gmailClient),
                'action_required' => $this->getUnreadCount('is:unread label:action-required', $gmailClient),
                'total_unread'    => $this->getUnreadCount('is:unread', $gmailClient),
                'today'           => $this->getUnreadCount('after:' . now()->format('Y/m/d'), $gmailClient),
            ];

        } catch (\Exception $e) {
            Log::debug('Gmail stats card failed to load', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function getUnreadCount(string $query, $gmailClient): int
    {
        try {
            $result = $gmailClient->listMessages(['q' => $query, 'maxResults' => 1]);
            return $result['resultSizeEstimate'] ?? count($result ?? []);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
