<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;

class RideSynced extends Notification implements ShouldQueue
{
    use Queueable;

    private Ride $ride;

    public function __construct(Ride $ride)
    {
        $this->ride = $ride;
    }

    public function via(): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->from('Ride Tracker')
            ->content("🚴 New ride synced: {$this->ride->name}")
            ->attachment(function ($attachment) {
                $attachment
                    ->fields([
                        [
                            'title' => '📏 Distance',
                            'value' => "{$this->ride->distance} mi",
                            'short' => true
                        ],
                        [
                            'title' => '🚀 Average Speed',
                            'value' => "{$this->ride->average_speed} mph",
                            'short' => true
                        ],
                        [
                            'title' => '💨 Max Speed',
                            'value' => "{$this->ride->max_speed} mph",
                            'short' => true
                        ],
                        [
                            'title' => '⛰️ Elevation',
                            'value' => "{$this->ride->elevation} ft",
                            'short' => true
                        ],
                        [
                            'title' => '🔥 Calories',
                            'value' => "{$this->ride->calories} cal",
                            'short' => true
                        ]
                    ])
                    ->image($this->ride->map_url);
            });
    }
}
