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
    /**
     * @var string|null
     */
    protected static ?string $model = Post::class;

    /**
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->required()
                    ->reactive()
                    ->maxLength(400),

                Forms\Components\Select::make(__('status'))
                    ->options([
                        'draft'     => __('Draft'),
                        'published' => __('Published'),
                    ])
                    ->default('draft')
                    ->required(),



                Forms\Components\Select::make('user_id')
                    ->Label('User')
                    ->options(
                        User::all()->pluck('name', 'id')->toArray()
                    )->default(Auth::user()->id)
                    ->required()->dehydrated(fn ($state) => Auth::user()->id),

                Forms\Components\MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'     => 'warning',
                        'published' => 'success',
                    })
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => __(ucfirst($state))),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
