<?php

namespace App\Events;

use Thunk\Verbs\Event;

class CommandFailed extends Event
{
    public string $command;

    public ?string $message;

    public function handle(): void
    {
        activity('command')
            ->event('failed')
            ->withProperties(['message' => $this->message, 'command' => $this->command])
            ->log('Command failed');
    }
}
