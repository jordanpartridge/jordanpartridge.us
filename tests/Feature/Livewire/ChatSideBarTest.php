<?php

use App\Livewire\ChatSideBar;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ChatSideBar::class)
        ->assertStatus(200);
});
