@props([
    'message',
    'isGitHubNotification' => false,
    'githubUrls' => []
])

<div class="space-y-4">
    <!-- GitHub Links (if available) -->
    @if ($isGitHubNotification && count($githubUrls) > 0)
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">GitHub Links</h5>
            <div class="flex flex-wrap gap-2">
                @foreach (array_slice($githubUrls, 0, 3) as $url)
                    <x-gmail.email-action-button
                        variant="github"
                        size="sm"
                        icon="external-link"
                        :href="$url"
                        target="_blank"
                        :label="$this->getGitHubLinkLabel($url, $message)">
                    </x-gmail.email-action-button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Primary Actions -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Actions</h5>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <!-- View Full Email -->
            <x-gmail.email-action-button
                variant="primary"
                icon="view"
                label="View Full Email"
                wire-click="showEmailPreview('{{ $message['id'] }}')"
                class="w-full justify-center">
            </x-gmail.email-action-button>

            <!-- Add Contact (for prospects/non-clients) -->
            @if (($message['category'] ?? '') === 'prospect_inquiry' || !($message['isClient'] ?? false))
                <x-gmail.email-action-button
                    variant="success"
                    icon="add-contact"
                    label="Add Contact"
                    wire-click="createContactFromEmail('{{ $message['id'] }}')"
                    class="w-full justify-center">
                </x-gmail.email-action-button>
            @endif

            <!-- GitHub Notifications (for GitHub emails) -->
            @if ($isGitHubNotification)
                <x-gmail.email-action-button
                    variant="github"
                    icon="github"
                    label="GitHub Notifications"
                    href="https://github.com/notifications"
                    target="_blank"
                    class="w-full justify-center">
                </x-gmail.email-action-button>
            @endif

            <!-- Delete Email -->
            <x-gmail.email-action-button
                variant="danger"
                icon="delete"
                label="Delete Email"
                wire-click="deleteEmail('{{ $message['id'] ?? '' }}')"
                class="w-full justify-center"
                wire:confirm="Are you sure you want to delete this email?">
            </x-gmail.email-action-button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Actions</h5>
        <div class="flex flex-wrap gap-2">
            @if (!($message['isRead'] ?? true))
                <x-gmail.email-action-button
                    variant="secondary"
                    size="sm"
                    label="Mark as Read"
                    wire-click="markAsRead('{{ $message['id'] }}')">
                </x-gmail.email-action-button>
            @endif

            <x-gmail.email-action-button
                variant="warning"
                size="sm"
                :label="($message['isStarred'] ?? false) ? 'Remove Star' : 'Add Star'"
                wire-click="toggleStar('{{ $message['id'] }}')">
            </x-gmail.email-action-button>

            @if ($isGitHubNotification)
                <x-gmail.email-action-button
                    variant="secondary"
                    size="sm"
                    label="Archive"
                    wire-click="archiveEmail('{{ $message['id'] }}')">
                </x-gmail.email-action-button>
            @endif
        </div>
    </div>
</div>