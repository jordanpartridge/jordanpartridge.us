<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\ClientDocument;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ClientDocumentStats extends BaseWidget
{
    protected static ?int $sort = 15;

    protected function getStats(): array
    {
        // Get total number of documents
        $totalDocuments = ClientDocument::count();

        // Get total storage used (converting from bytes to MB)
        $totalStorageBytes = ClientDocument::sum('file_size');
        $totalStorageMB = round($totalStorageBytes / 1048576, 2); // Convert bytes to MB

        // Get document type distribution
        $documentTypes = ClientDocument::query()
            ->select(
                DB::raw('CASE
                    WHEN mime_type LIKE "%pdf%" THEN "PDF"
                    WHEN mime_type LIKE "%word%" OR mime_type LIKE "%document%" THEN "Word"
                    WHEN mime_type LIKE "%excel%" OR mime_type LIKE "%sheet%" THEN "Excel"
                    WHEN mime_type LIKE "%image%" THEN "Image"
                    ELSE "Other"
                END as type'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'type')
            ->toArray();

        // Format document type distribution for the chart
        $documentTypeChart = [
            'data'   => array_values($documentTypes),
            'labels' => array_keys($documentTypes),
        ];

        // Get average documents per client
        $clientsWithDocs = Client::has('documents')->count();
        $avgDocsPerClient = $clientsWithDocs > 0
            ? round(ClientDocument::count() / $clientsWithDocs, 1)
            : 0;

        // Get upload trends for the last 6 months
        $uploadTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = ClientDocument::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $uploadTrend[] = $count;
        }

        return [
            Stat::make('Total Documents', $totalDocuments)
                ->description('Total documents uploaded')
                ->descriptionIcon('heroicon-m-document')
                ->chart($uploadTrend)
                ->color('primary'),

            Stat::make('Storage Used', $totalStorageMB . ' MB')
                ->description('Total storage consumption')
                ->descriptionIcon('heroicon-m-cloud')
                ->color('warning'),

            Stat::make('Document Types', implode(', ', array_map(fn ($type, $count) => "$type: $count", array_keys($documentTypes), array_values($documentTypes))))
                ->description('Distribution by file type')
                ->descriptionIcon('heroicon-m-document-chart-bar')
                ->color('success'),

            Stat::make('Avg. Documents', $avgDocsPerClient)
                ->description('Per client with documents')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray'),
        ];
    }
}
