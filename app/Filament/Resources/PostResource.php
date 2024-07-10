<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Tabs::make('Heading')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Details')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Featured Image')
                                            ->image()
                                            ->disk('public')
                                            ->required()
                                            ->columnSpanFull(),

                                        TextInput::make('title')
                                            ->label('Post Title')
                                            ->required()
                                            ->reactive()
                                            ->maxLength(400)
                                            ->placeholder('Enter the title of the post'),

                                        Forms\Components\Select::make('status')
                                            ->label('Post Status')
                                            ->options([
                                                'draft'     => 'Draft',
                                                'published' => 'Published',
                                            ])
                                            ->default('draft')
                                            ->required(),

                                        Forms\Components\Select::make('user_id')
                                            ->label('Author')
                                            ->options(User::all()->pluck('name', 'id')->toArray())
                                            ->default(Auth::user()->id)
                                            ->required()
                                            ->dehydrated(fn ($state) => Auth::user()->id),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Content')
                                    ->schema([
                                       Forms\Components\RichEditor::make('body')
                                            ->label('Post Content')
                                            ->required()
                                            ->columnSpanFull()
                                           ->toolbarButtons([
                                               'attachFiles',
                                               'blockquote',
                                               'bold',
                                               'bulletList',
                                               'codeBlock',
                                               'h2',
                                               'h3',
                                               'italic',
                                               'link',
                                               'orderedList',
                                               'redo',
                                               'strike',
                                               'underline',
                                               'undo',
                                           ])
                                    ]),
                                Forms\Components\Tabs\Tab::make('SEO')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->placeholder('Enter the meta title'),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->placeholder('Enter the meta description'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'     => 'warning',
                        'published' => 'success',
                    })
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->label('Filter by Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
