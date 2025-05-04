<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class ContactFormSubmitted extends Notification
{
    use Queueable;

    public array $formData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $formData)
    {
        $this->formData = $formData;
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
     * Get the Slack representation of the notification.
     */
    public function toSlack($notifiable): SlackMessage
    {
        $message = (new SlackMessage())
            ->headerBlock('🎉 New Contact Form Submission! 🎉')
            ->sectionBlock(function (SectionBlock $section) {
                $section->text(':star2: *You have a new contact form submission!* :star2:')
                    ->markdown();
            })
            ->dividerBlock();

        foreach ($this->formData as $key => $value) {
            if ($value) {
                $message->sectionBlock(function (SectionBlock $section) use ($key, $value) {
                    $key = ucfirst($key);

                    $section->text("*{$key}:* {$value}")
                        ->markdown();
                });
            }
        }

        $message->dividerBlock()
            ->sectionBlock(function (SectionBlock $section) {
                $section->text(':wave: *Please respond to this inquiry as soon as possible!*')
                    ->markdown();
            });

        return $message;
    }
}
