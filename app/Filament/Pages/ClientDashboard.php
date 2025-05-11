<?php

namespace App\Filament\Pages;

use App\Models\Client;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClientDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.client-dashboard';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Client Dashboard';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Client Management Dashboard';

    protected static ?array $widgets = [
        \App\Filament\Widgets\ClientDocumentStats::class,
        \App\Filament\Widgets\ClientsNeedingAttention::class,
        \App\Filament\Widgets\ClientActivityChart::class,
    ];

    // Form state
    public ?array $documentData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $focusedClient = $this->getFocusedClient();

        return $form
            ->schema([
                FileUpload::make('document')
                    ->label('Select Document')
                    ->disk('s3')
                    ->directory('client-documents')
                    ->visibility('private')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/msword',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'image/*'
                    ])
                    ->maxSize(10240) // 10MB
                    ->preserveFilenames()
                    ->required(),

                Textarea::make('description')
                    ->label('Document Description')
                    ->maxLength(255)
                    ->nullable(),
            ])
            ->statePath('documentData');
    }

    /**
     * Save an uploaded document to the client's records
     */
    public function saveDocument(): void
    {
        $focusedClient = $this->getFocusedClient();

        if (!$focusedClient) {
            Notification::make()
                ->title('Error')
                ->body('No focused client selected.')
                ->danger()
                ->send();
            return;
        }

        // Authorize the current user to add documents to this client
        if (Auth::user()->cannot('update', $focusedClient)) {
            Notification::make()
                ->title('Access Denied')
                ->body('You do not have permission to upload documents for this client.')
                ->danger()
                ->send();
            return;
        }

        try {
            // Validate form state
            $data = $this->form->getState();

            // Generate a secure unique filename with client prefixed path
            $file = $data['document'];
            $safeName = $focusedClient->id . '_' . time() . '_' . hash('xxh3', $file->getClientOriginalName());
            $extension = $file->getClientOriginalExtension();
            $uniquePath = 'client-documents/' . $safeName . '.' . $extension;

            // Upload to secure storage with private access
            $path = Storage::disk('s3')->putFileAs(
                'client-documents',
                $file,
                $safeName . '.' . $extension,
                ['visibility' => 'private']
            );

            // Validate file was uploaded successfully
            if (!$path) {
                throw new Exception('Document upload failed');
            }

            // Scan file for viruses if the functionality is available
            // This would require a virus scanning service or library

            // Create document record
            $document = $focusedClient->documents()->create([
                'uploaded_by'       => Auth::id(),
                'filename'          => $uniquePath,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type'         => $file->getClientMimeType(),
                'file_size'         => $file->getSize(),
                'description'       => $data['description'] ?? null,
            ]);

            // Log the activity with details
            activity()
                ->causedBy(Auth::user())
                ->performedOn($focusedClient)
                ->withProperties([
                    'document_id' => $document->id,
                    'filename'    => $document->original_filename,
                    'file_size'   => $document->file_size_for_humans,
                ])
                ->log('uploaded_document');

            // Success notification
            Notification::make()
                ->title('Success')
                ->body('Document uploaded successfully.')
                ->success()
                ->send();

            // Reset form
            $this->form->fill();

        } catch (Exception $e) {
            // Log error but don't expose details to user
            Log::error('Document upload failed: ' . $e->getMessage(), [
                'client_id' => $focusedClient->id,
                'user_id'   => Auth::id(),
                'exception' => $e,
            ]);

            // User friendly error
            Notification::make()
                ->title('Upload Failed')
                ->body('There was a problem uploading your document. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function getFocusedClient(): ?Client
    {
        return Client::focused()
            ->with(['documents', 'documents.uploadedBy'])
            ->orderBy('id')
            ->first();
    }

    protected function getViewData(): array
    {
        $focusedClient = $this->getFocusedClient();

        return [
            'focusedClient' => $focusedClient,
            'documents'     => $focusedClient?->documents ?? collect([]),
        ];
    }
}
