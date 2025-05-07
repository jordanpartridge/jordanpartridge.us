<?php

namespace App\Filament\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;

class ClientExport
{
    public function collection(): Collection
    {
        return Client::query()
            ->with('user')
            ->get()
            ->map(function (Client $client) {
                return [
                    'ID'              => $client->id,
                    'Name'            => $client->name,
                    'Company'         => $client->company ?? '',
                    'Email'           => $client->email,
                    'Phone'           => $client->phone ?? '',
                    'Website'         => $client->website ?? '',
                    'Status'          => $client->status->value ?? '',
                    'Account Manager' => $client->user?->name ?? '',
                    'Last Contact'    => $client->last_contact_at?->format('Y-m-d') ?? '',
                    'Notes'           => strip_tags($client->notes ?? ''),
                    'Created'         => $client->created_at->format('Y-m-d'),
                    'Updated'         => $client->updated_at->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Company',
            'Email',
            'Phone',
            'Website',
            'Status',
            'Account Manager',
            'Last Contact',
            'Notes',
            'Created',
            'Updated',
        ];
    }

    public function filename(): string
    {
        return 'clients-' . now()->format('Y-m-d') . '.csv';
    }
}
