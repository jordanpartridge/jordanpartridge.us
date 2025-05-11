<?php

namespace App\Http\Controllers;

use App\Filament\Exports\ClientExport;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        // Create export object and apply filters
        $export = new ClientExport();

        // Apply status filters
        if ($request->has('status')) {
            $statuses = is_array($request->status) ? $request->status : [$request->status];
            $export->filterByStatus($statuses);
        }

        // Apply user/account manager filter
        if ($request->has('user_id')) {
            $export->filterByUser((int) $request->user_id);
        }

        // Apply date range filter
        if ($request->has(['start_date', 'end_date'])) {
            $export->filterByDateRange(
                $request->start_date,
                $request->end_date
            );
        }

        // Apply focused filter
        if ($request->boolean('focused')) {
            $export->onlyFocused();
        }

        // Generate filename
        $filename = $export->filename();

        // Use chunked streaming for memory efficiency
        return response()->streamDownload(function () use ($export) {
            $output = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fputs($output, "\xEF\xBB\xBF");

            // Use memory-efficient chunked export
            $export->exportChunked($output, 200);

            fclose($output);
        }, $filename, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
