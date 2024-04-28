<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class SlackEventReceived extends Notification
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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


    public function toSlack(object $notifiable): SlackMessage
    {
        $requestData = json_encode($this->request->all(), JSON_PRETTY_PRINT);

        return (new SlackMessage())
            ->headerBlock('📬 New Event Received')
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) use ($requestData) {
                $block->text("*Event Details:*\n```{$requestData}```")->markdown();
            })
            ->dividerBlock()
            ->contextBlock(function (ContextBlock $block) {
                $block->text('🕒 ' . now()->format('Y-m-d H:i:s'));
            });
    }
}
