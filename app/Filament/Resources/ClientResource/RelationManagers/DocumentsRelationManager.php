<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'original_filename';

    protected static ?string $title = 'Documents';

    public function form(Form $form): Form
    {
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
                    ->hiddenLabel()
                    ->required(),

                Textarea::make('description')
                    ->label('Document Description')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_filename')
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('mime_type')
                    ->label('')
                    ->icon(fn ($record) => match (true) {
                        str_contains($record->mime_type, 'pdf')                                                      => 'heroicon-o-document',
                        str_contains($record->mime_type, 'word')                                                     => 'heroicon-o-document-text',
                        str_contains($record->mime_type, 'excel') || str_contains($record->mime_type, 'spreadsheet') => 'heroicon-o-document-chart-bar',
                        str_contains($record->mime_type, 'image')                                                    => 'heroicon-o-photo',
                        default                                                                                      => 'heroicon-o-document',
                    })
                    ->color(fn ($record) => match (true) {
                        str_contains($record->mime_type, 'pdf')                                                      => 'danger',
                        str_contains($record->mime_type, 'word')                                                     => 'primary',
                        str_contains($record->mime_type, 'excel') || str_contains($record->mime_type, 'spreadsheet') => 'success',
                        str_contains($record->mime_type, 'image')                                                    => 'warning',
                        default                                                                                      => 'gray',
                    }),

                TextColumn::make('original_filename')
                    ->label('Filename')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('description')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('file_size_for_humans')
                    ->label('Size'),

                TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date Uploaded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Upload Document')
                    ->using(function (array $data, RelationManager $livewire): void {
                        $file = $data['document'];
                        $path = Storage::disk('s3')->putFile('client-documents', $file);

                        $livewire->getOwnerRecord()->documents()->create([
                            'uploaded_by'       => Auth::id(),
                            'filename'          => $path,
                            'original_filename' => $file->getClientOriginalName(),
                            'mime_type'         => $file->getClientMimeType(),
                            'file_size'         => $file->getSize(),
                            'description'       => $data['description'] ?? null,
                        ]);
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => route('client-documents.download', $record))
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
