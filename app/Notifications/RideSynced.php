<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class RideSynced extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Ride $ride,
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
        $message = (new SlackMessage())
            ->text('New cycling achievement unlocked! 🚴' . $this->ride->name)
            ->contextBlock(function (ContextBlock $block) {
                $block->text("Ride from {$this->ride->date->format('F j, Y')}");
            });

        $message->sectionBlock(function (SectionBlock $block) {
            $block->text("*{$this->ride->name}*")->markdown();

        });


        // Add ride stats
        $message->sectionBlock(function (SectionBlock $block) {
            $block->field("*Distance*\n{$this->ride->distance} miles")->markdown();
            $block->field("*Moving Time*\n{$this->ride->moving_time}")->markdown();
            $block->field("*Avg Speed*\n{$this->ride->average_speed} mph")->markdown();
        });

        return $message;
    }

}
