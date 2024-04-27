<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class RideSynced extends Notification
{
    use Queueable;

    private mixed $ride;

    /**
     * Create a new notification instance.
     */
    public function __construct($ride)
    {
        $this->ride = $ride;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $googleMapsApiKey = config('services.google_maps.key'); // Replace with your actual Google Maps API key
        $polyline = $this->ride->polyline; // Assuming this is a string of encoded polyline data

        $staticMapUrl = "https://maps.googleapis.com/maps/api/staticmap?size=600x300&path=enc:{$polyline}&key={$googleMapsApiKey}";

        return (new SlackMessage())
            ->headerBlock('🚴 Ride Synced: ' . $this->ride->name)
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*🔥 Calories Burned:*\n" . $this->ride->calories)->markdown();
                $block->field("*📏 Distance Covered:*\n" . $this->ride->distance . ' miles')->markdown();
            })
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*⛰️ Elevation Gain:*\n" . $this->ride->elevation . ' ft')->markdown();
                $block->field("*💨 Max Speed:*\n" . $this->ride->max_speed . ' mph')->markdown();
            })
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*🚀 Average Speed:*\n" . $this->ride->average_speed . ' mph')->markdown();
                $block->field("*⏱️ Moving Time:*\n" . $this->ride->moving_time)->markdown();
            })
            ->dividerBlock()
            ->imageBlock($staticMapUrl, 'Map of the ride')
            ->dividerBlock()
            ->contextBlock(function (ContextBlock $block) {
                $block->text('🎉 Great job on your ride! Keep up the good work!');
            });
    }
}
