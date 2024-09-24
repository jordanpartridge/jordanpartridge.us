<?php

namespace App\Events;

use Thunk\Verbs\Event;

class CommandFailed extends Event
{
    public string $message;
    public string $command;

    public function handle(): void
    {
        activity('command:' . $this->command)
            ->event('failed')
            ->log($this->message);
    }
}
