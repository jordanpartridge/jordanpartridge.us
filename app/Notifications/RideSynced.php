<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ActionsBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Support\Facades\Storage;

class RideSynced extends Notification implements ShouldQueue
{
    use Queueable;

    private Ride $ride;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ride $ride)
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
        return ['mail', 'slack', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('🚴 Ride Synced: ' . $this->ride->name)
            ->greeting('Hey there, rider! 👋')
            ->line('Your awesome ride has been successfully synced. Check out your stats!')
            ->action('View Ride Details', url("/rides/{$this->ride->id}"))
            ->line('Keep pushing those pedals! 💪');
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        $rideUrl = url("/rides/{$this->ride->id}");
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($rideUrl);
        $mapUrl = $this->getTemporaryMapUrl();

        return (new SlackMessage())
            ->headerBlock('🚴 ' . $this->ride->name)
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('🗓️ ' . $this->formatDate($this->ride->start_date));
            })
            ->imageBlock($mapUrl, 'Route Map')
            ->sectionBlock(function (SectionBlock $block) {
                $block->text($this->getStatsMarkdown());
            })
            ->contextBlock(function (ContextBlock $block) use ($qrCodeUrl) {
                $block->image($qrCodeUrl, 'QR Code for ride details');
                $block->text('Scan for full details (or tap the button below on mobile)');
            })
            ->actionsBlock(function (ActionsBlock $block) use ($rideUrl) {
                $block->button('📊 View Full Stats')->url($rideUrl)->style('primary');
                $block->button('🌟 Share Ride')->url('https://www.strava.com/upload/manual');
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ride_id'    => $this->ride->id,
            'name'       => $this->ride->name,
            'start_date' => $this->ride->start_date->toIso8601String(),
            'end_date'   => $this->ride->end_date->toIso8601String(),
            'distance'   => [
                'value'     => $this->ride->distance,
                'unit'      => 'miles',
                'formatted' => $this->formatNumber($this->ride->distance, 2) . ' mi',
            ],
            'duration' => [
                'value'     => $this->ride->moving_time,
                'formatted' => $this->formatDuration($this->ride->moving_time),
            ],
            'speed' => [
                'average' => [
                    'value'     => $this->ride->average_speed,
                    'unit'      => 'mph',
                    'formatted' => $this->formatNumber($this->ride->average_speed, 1) . ' mph',
                ],
                'max' => [
                    'value'     => $this->ride->max_speed,
                    'unit'      => 'mph',
                    'formatted' => $this->formatNumber($this->ride->max_speed, 1) . ' mph',
                ],
            ],
            'elevation' => [
                'value'     => $this->ride->elevation,
                'unit'      => 'feet',
                'formatted' => $this->formatNumber($this->ride->elevation) . ' ft',
            ],
            'calories' => [
                'value'     => $this->ride->calories,
                'unit'      => 'cal',
                'formatted' => $this->formatNumber($this->ride->calories) . ' cal',
            ],
            'map_url'     => $this->getTemporaryMapUrl(),
            'details_url' => url("/rides/{$this->ride->id}"),
            'share_url'   => 'https://www.strava.com/upload/manual',
        ];
    }

    /**
     * Generate the stats markdown for Slack notification.
     */
    private function getStatsMarkdown(): string
    {
        return "*📏 Distance*\n{$this->formatNumber($this->ride->distance, 2)} mi\n\n" .
            "*⏱️ Duration*\n{$this->formatDuration($this->ride->moving_time)}\n\n" .
            "*🚀 Avg Speed*\n{$this->formatNumber($this->ride->average_speed, 1)} mph\n\n" .
            "*💨 Max Speed*\n{$this->formatNumber($this->ride->max_speed, 1)} mph\n\n" .
            "*⛰️ Elevation*\n{$this->formatNumber($this->ride->elevation)} ft\n\n" .
            "*🔥 Calories*\n{$this->formatNumber($this->ride->calories)} cal";
    }

    /**
     * Get a temporary URL for the map image.
     */
    private function getTemporaryMapUrl(): string
    {
        return Storage::disk('s3')->temporaryUrl($this->ride->map_url, now()->addMinutes(5));
    }

    /**
     * Format a number with the specified number of decimal places.
     */
    private function formatNumber(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals);
    }

    /**
     * Format the duration in HH:MM:SS format.
     */
    private function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    /**
     * Format the date in a compact, readable format.
     */
    private function formatDate(\DateTime $date): string
    {
        return $date->format('D, M j, Y');
    }
}
