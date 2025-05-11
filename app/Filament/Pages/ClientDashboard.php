<?php

namespace App\Filament\Pages;

use App\Models\Client;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.client-dashboard';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Client Dashboard';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Client Management Dashboard';

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

        $this->form->getState();

        $file = $this->documentData['document'];
        $path = Storage::disk('s3')->putFile('client-documents', $file);

        $focusedClient->documents()->create([
            'uploaded_by'       => Auth::id(),
            'filename'          => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type'         => $file->getClientMimeType(),
            'file_size'         => $file->getSize(),
            'description'       => $this->documentData['description'] ?? null,
        ]);

        Notification::make()
            ->title('Success')
            ->body('Document uploaded successfully.')
            ->success()
            ->send();

        $this->form->fill();
    }

    public function getFocusedClient(): ?Client
    {
        return Client::where('is_focused', true)->first();
    }

    protected function getViewData(): array
    {
        $focusedClient = $this->getFocusedClient();

        return [
            'focusedClient' => $focusedClient,
            'documents'     => $focusedClient?->documents()->latest()->get() ?? collect([]),
        ];
    }
}
