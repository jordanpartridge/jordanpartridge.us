<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        $this->handleFocusChange($client);
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        $this->handleFocusChange($client);
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        // If this was the focused client, clear the focus since it's being deleted
        if ($client->is_focused) {
            // No need to modify the client being deleted, it's already being removed
        }
    }

    /**
     * Handle client focus changes to ensure only one client is focused
     */
    private function handleFocusChange(Client $client): void
    {
        // If this client is being focused, remove focus from all other clients
        if ($client->is_focused) {
            DB::table('clients')
                ->where('id', '!=', $client->id)
                ->where('is_focused', true)
                ->update(['is_focused' => false]);
        }
    }
}
