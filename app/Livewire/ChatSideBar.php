<?php

namespace App\Livewire;

use Livewire\Component;

class ChatSideBar extends Component
{
    public $panelHidden = true;
    public $messages = [];
    public $question = '';
    public $isLoading = false;
    public $winPosition = 'right';
    public $winWidth = 'width: 400px;';
    public $name = 'AI Assistant';

    public function mount()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Hello! How can I help you today?']
        ];
    }

    public function sendMessage()
    {
        if (empty(trim($this->question))) {
            return;
        }

        $this->messages[] = [
            'role'    => 'user',
            'content' => $this->question
        ];

        $this->question = '';
        $this->dispatch('sendmessage');
    }

    public function resetSession()
    {
        $this->messages = [
            ['role' => 'assistant', 'content' => 'Hello! How can I help you today?']
        ];
    }

    public function changeWinWidth()
    {
        $this->winWidth = $this->winWidth === 'width: 400px;' ? 'width:100%;' : 'width: 400px;';
    }

    public function changeWinPosition()
    {
        $this->winPosition = $this->winPosition === 'left' ? 'right' : 'left';
    }
}
