<?php

namespace App\Filament\Exports;

use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClientExport
{
    // Export filters
    protected ?array $statusFilter = null;
    protected ?int $userFilter = null;
    protected ?Carbon $startDate = null;
    protected ?Carbon $endDate = null;
    protected ?bool $onlyFocused = null;

    /**
     * Set status filter for export
     */
    public function filterByStatus(?array $statuses): self
    {
        $this->statusFilter = $statuses;
        return $this;
    }

    /**
     * Set account manager filter for export
     */
    public function filterByUser(?int $userId): self
    {
        $this->userFilter = $userId;
        return $this;
    }

    /**
     * Set date range filter for created_at
     */
    public function filterByDateRange(?string $start = null, ?string $end = null): self
    {
        $this->startDate = $start ? Carbon::parse($start) : null;
        $this->endDate = $end ? Carbon::parse($end)->endOfDay() : null;
        return $this;
    }

    /**
     * Set focused clients only filter
     */
    public function onlyFocused(?bool $focused = true): self
    {
        $this->onlyFocused = $focused;
        return $this;
    }

    /**
     * Process export data in chunks to conserve memory
     */
    public function exportChunked($output, int $chunkSize = 100): void
    {
        // Write headers
        fputcsv($output, $this->headings());

        // Process in chunks
        $this->getQuery()->orderBy('id')->chunk($chunkSize, function ($clients) use ($output) {
            foreach ($clients as $client) {
                fputcsv($output, [
                    $client->id,
                    $client->name,
                    $client->company ?? '',
                    $client->email,
                    $client->phone ?? '',
                    $client->website ?? '',
                    $client->status->value ?? '',
                    $client->user?->name ?? '',
                    $client->last_contact_at?->format('Y-m-d H:i:s') ?? '',
                    strip_tags($client->notes ?? ''),
                    $client->created_at->format('Y-m-d H:i:s'),
                    $client->updated_at->format('Y-m-d H:i:s'),
                    $client->is_focused ? 'Yes' : 'No',
                    $client->documents_count ?? 0,
                ]);
            }
        });
    }

    /**
     * Get the entire collection for smaller exports
     */
    public function collection(): Collection
    {
        return $this->getQuery()
            ->withCount('documents')
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
                    'Last Contact'    => $client->last_contact_at?->format('Y-m-d H:i:s') ?? '',
                    'Notes'           => strip_tags($client->notes ?? ''),
                    'Created'         => $client->created_at->format('Y-m-d H:i:s'),
                    'Updated'         => $client->updated_at->format('Y-m-d H:i:s'),
                    'Dashboard Focus' => $client->is_focused ? 'Yes' : 'No',
                    'Document Count'  => $client->documents_count ?? 0,
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
            'Dashboard Focus',
            'Document Count',
        ];
    }

    public function filename(): string
    {
        $timestamp = now()->format('Y-m-d-His');

        // Add filter info to filename
        $parts = ['clients'];

        if ($this->statusFilter) {
            $parts[] = 'status-' . implode('-', $this->statusFilter);
        }

        if ($this->userFilter) {
            $userId = $this->userFilter;
            // Cache manager name to avoid repeated DB lookups
            $managerName = Cache::remember("user-name-{$userId}", 3600, function () use ($userId) {
                $user = User::find($userId);
                return $user ? strtolower(str_replace(' ', '-', $user->name)) : 'unknown';
            });
            $parts[] = 'manager-' . $managerName;
        }

        if ($this->onlyFocused) {
            $parts[] = 'focused';
        }

        return implode('-', $parts) . '-' . $timestamp . '.csv';
    }

    /**
     * Build the query with applied filters
     */
    protected function getQuery(): Builder
    {
        $query = Client::query()
            ->with('user');

        // Apply filters if set
        if ($this->statusFilter) {
            $query->whereIn('status', $this->statusFilter);
        }

        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        if ($this->startDate) {
            $query->where('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('created_at', '<=', $this->endDate);
        }

        if ($this->onlyFocused) {
            $query->where('is_focused', true);
        }

        return $query;
    }
}
