<?php

namespace App\States;

use Thunk\Verbs\State;

class UserState extends State
{
    public string $name;

    public string $email;

    public string $password;
}
