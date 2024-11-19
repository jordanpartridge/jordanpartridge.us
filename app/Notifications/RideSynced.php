<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class RideSynced extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Ride $ride
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(mixed $notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->success()
            ->content("🚴 New ride synced: {$this->ride->name}")
            ->attachment(function (SlackAttachment $attachment): SlackAttachment {
                return $attachment
                    ->fields([
                        $this->createField('📏 Distance', "{$this->ride->distance} mi"),
                        $this->createField('🚀 Average Speed', "{$this->ride->average_speed} mph"),
                        $this->createField('💨 Max Speed', "{$this->ride->max_speed} mph"),
                        $this->createField('⛰️ Elevation', "{$this->ride->elevation} ft"),
                        $this->createField('🔥 Calories', "{$this->ride->calories} cal"),
                    ])
                    ->image($this->ride->map_url);
            });
    }

    /**
     * Create a field for the Slack attachment.
     *
     * @return array<string, mixed>
     */
    private function createField(string $title, string $value): array
    {
        return [
            'title' => $title,
            'value' => $value,
            'short' => true,
        ];
    }
}
