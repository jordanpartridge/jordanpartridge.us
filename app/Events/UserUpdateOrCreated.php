<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;

class UserUpdateOrCreated extends Event
{
    public array $queryArray = [];
    public array $updateArray = [];
    public function handle(): User
    {
        return User::updateOrCreate($this->queryArray, $this->updateArray);
    }
}
