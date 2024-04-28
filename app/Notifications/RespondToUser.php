<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;

class RespondToUser extends Notification
{
    use Queueable;

    private string $message;
    private $userId;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
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
        //determine if the message appears to be  a greeting and respond accordingly

        $response = $this->isGreeting($this->message) ? 'Hi there, <@' . $this->userId . '>!' : '👋 <@' . $this->userId . '>';
        return (new SlackMessage())
            ->sectionBlock(
                function ($block) use ($response) {
                    $block->text($response);
                }
            )
            ->contextBlock(function ($block) {
                $block->text('🕒 ' . now()->format('Y-m-d H:i:s'));
            });

    }


    private function isGreeting(string $message): bool
    {
        $greetings = ['hello', 'hi', 'hey', 'greetings', 'good day'];

        foreach ($greetings as $greeting) {
            if (stripos($message, $greeting) !== false) {
                return true;
            }
        }

        return false;
    }
}
