<?php

namespace App\Http\Controllers;

use App\Filament\Exports\ClientExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $export = new ClientExport();
        $filename = $export->filename();
        $collection = $export->collection();
        $headings = $export->headings();

        return response()->streamDownload(function () use ($collection, $headings) {
            $output = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fputs($output, "\xEF\xBB\xBF");

            // Add headings
            fputcsv($output, $headings);

            // Add rows
            foreach ($collection as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
