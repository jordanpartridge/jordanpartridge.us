<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Exception;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Blog Management';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs([
                                Tab::make('Details')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                FileUpload::make('image')
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
                                                    ->placeholder('Enter the title of the post')
                                                    ->columnSpan(1),
                                                    
                                                Select::make('status')
                                                    ->label('Post Status')
                                                    ->options([
                                                        'draft'     => 'Draft',
                                                        'published' => 'Published',
                                                    ])
                                                    ->default('draft')
                                                    ->required()
                                                    ->columnSpan(1),
                                                    
                                                Textarea::make('excerpt')
                                                    ->label('Excerpt')
                                                    ->maxLength(300)
                                                    ->hint('A short summary of the post content')
                                                    ->columnSpanFull(),
                                                    
                                                Select::make('type')
                                                    ->label('Content Type')
                                                    ->options([
                                                        'post' => 'Blog Post',
                                                        'page' => 'Page',
                                                    ])
                                                    ->default('post')
                                                    ->required()
                                                    ->columnSpan(1),
                                                    
                                                Checkbox::make('featured')
                                                    ->label('Featured Post')
                                                    ->helperText('Display this post in featured sections')
                                                    ->columnSpan(1),
                                                    
                                                Select::make('user_id')
                                                    ->label('Author')
                                                    ->relationship('user', 'name')
                                                    ->default(Auth::user()->id)
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->columnSpan(1),
                                                    
                                                Select::make('categories')
                                                    ->label('Categories')
                                                    ->relationship('categories', 'name')
                                                    ->multiple()
                                                    ->preload()
                                                    ->searchable()
                                                    ->createOptionForm([
                                                        TextInput::make('name')
                                                            ->required()
                                                            ->maxLength(255),
                                                        ColorPicker::make('color')
                                                            ->required(),
                                                    ])
                                                    ->columnSpan(1),
                                            ]),
                                    ]),
                                Tab::make('Content')
                                    ->schema([
                                        RichEditor::make('body')
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
                                            ]),
                                    ]),
                                Tab::make('SEO')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->placeholder('Enter the meta title')
                                            ->helperText('Optimal length: 50-60 characters'),
                                        
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->placeholder('Enter the meta description')
                                            ->helperText('Optimal length: 150-160 characters'),
                                            
                                        Textarea::make('meta_schema')
                                            ->label('Schema Markup')
                                            ->placeholder('Enter JSON-LD schema markup')
                                            ->helperText('Optional: Add structured data for better SEO')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->toggleable(),
                    
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'     => 'warning',
                        'published' => 'success',
                    })
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->color(fn ($record) => $record->categories->isNotEmpty() ? $record->categories->first()->color : 'gray'),
                    
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->toggleable(),
                    
                CheckboxColumn::make('featured')
                    ->label('Featured')
                    ->toggleable(),
                
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
                    ])
                    ->label('Filter by Status'),
                    
                SelectFilter::make('type')
                    ->options([
                        'post' => 'Blog Post',
                        'page' => 'Page',
                    ])
                    ->label('Filter by Type'),
                    
                SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Category'),
                    
                TernaryFilter::make('featured')
                    ->label('Featured Posts'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit'   => EditPost::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'categories']);
    }
}