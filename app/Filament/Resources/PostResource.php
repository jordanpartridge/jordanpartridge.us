<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Exception;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Services\AI\AIContentService;

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
                                                Toggle::make('featured')
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
                                        TextInput::make('preview_social')
                                            ->label('Preview Social Media')
                                            ->hidden()
                                            ->suffixAction(
                                                Action::make('previewSocial')
                                                    ->label('Preview Social Media Posts')
                                                    ->icon('heroicon-o-eye')
                                                    ->action(function (Get $get) {
                                                        $title = $get('title');
                                                        $body = $get('body');
                                                        $excerpt = $get('excerpt');

                                                        if (empty($title) || empty($body)) {
                                                            Notification::make()
                                                                ->title('Insufficient content')
                                                                ->body('Please add a title and content first')
                                                                ->warning()
                                                                ->send();
                                                            return;
                                                        }

                                                        // Add modal invocation here
                                                        $this->dispatch('open-modal', id: 'social-preview-modal', data: [
                                                            'title'   => $title,
                                                            'body'    => $body,
                                                            'excerpt' => $excerpt
                                                        ]);
                                                    })
                                            ),
                                    ]),
                                Tab::make('seo')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('meta_title')
                                                    ->label('Meta Title')
                                                    ->maxLength(60)
                                                    ->placeholder('Enter the meta title')
                                                    ->helperText('Optimal length: 50-60 characters')
                                                    ->columnSpan(1),
                                                TextInput::make('generate_seo')
                                                    ->label('SEO Metadata')
                                                    ->hidden()
                                                    ->suffixAction(
                                                        Action::make('generateSeo')
                                                            ->label('Generate with AI')
                                                            ->icon('heroicon-o-sparkles')
                                                            ->action(function (Get $get, $set) {
                                                                $title = $get('title');
                                                                $body = $get('body');

                                                                if (empty($title) || empty($body)) {
                                                                    Notification::make()
                                                                        ->title('Cannot generate SEO')
                                                                        ->body('Please add a title and content first')
                                                                        ->warning()
                                                                        ->send();
                                                                    return;
                                                                }

                                                                // Create a temporary Post object
                                                                $tempPost = new Post([
                                                                    'title' => $title,
                                                                    'body'  => $body,
                                                                ]);

                                                                try {
                                                                    $aiService = app(AIContentService::class);
                                                                    $seoData = $aiService->generateSeoMetadata($tempPost);

                                                                    $set('meta_title', $seoData['meta_title']);
                                                                    $set('meta_description', $seoData['meta_description']);
                                                                    $set('meta_keywords', implode(', ', $seoData['keywords']));

                                                                    Notification::make()
                                                                        ->title('SEO metadata generated')
                                                                        ->success()
                                                                        ->send();
                                                                } catch (\Exception $e) {
                                                                    Notification::make()
                                                                        ->title('Failed to generate SEO metadata')
                                                                        ->body($e->getMessage())
                                                                        ->danger()
                                                                        ->send();
                                                                }
                                                            })
                                                    ),
                                            ]),
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->placeholder('Enter the meta description')
                                            ->helperText('Optimal length: 150-160 characters'),
                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->placeholder('keyword1, keyword2, keyword3')
                                            ->helperText('Comma-separated keywords'),
                                        Textarea::make('meta_schema')
                                            ->label('Schema Markup')
                                            ->placeholder('Enter JSON-LD schema markup')
                                            ->helperText('Optional: Add structured data for better seo')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('social_media')
                                    ->label('Social Media')
                                    ->schema([
                                        Toggle::make('auto_generate_social')
                                            ->label('Automatically generate social media posts')
                                            ->helperText('Generate content for social platforms when post is published')
                                            ->reactive()
                                            ->default(true),

                                        CheckboxList::make('social_platforms')
                                            ->label('Social Platforms')
                                            ->options([
                                                'linkedin' => 'LinkedIn',
                                                'twitter'  => 'Twitter/X'
                                            ])
                                            ->default(['linkedin', 'twitter'])
                                            ->visible(fn ($get) => $get('auto_generate_social')),

                                        ViewField::make('linkedin_preview')
                                            ->view('filament.forms.components.social-preview', [
                                                'platform'       => 'linkedin',
                                                'renderCallback' => function ($get) {
                                                    // Only show preview if we have content and title
                                                    if (!$get('title') || !$get('body')) {
                                                        return null;
                                                    }

                                                    // Create a temporary Post object for preview
                                                    $tempPost = new Post([
                                                        'title'   => $get('title'),
                                                        'body'    => $get('body'),
                                                        'excerpt' => $get('excerpt'),
                                                    ]);

                                                    $aiService = app(AIContentService::class);
                                                    return $aiService->generateSocialPost($tempPost, 'linkedin');
                                                }
                                            ])
                                            ->columnSpanFull()
                                            ->visible(
                                                fn ($get) => $get('auto_generate_social') &&
                                                in_array('linkedin', $get('social_platforms') ?? []) &&
                                                $get('title') &&
                                                $get('body')
                                            ),

                                        ViewField::make('twitter_preview')
                                            ->view('filament.forms.components.social-preview', [
                                                'platform'       => 'twitter',
                                                'renderCallback' => function ($get) {
                                                    // Only show preview if we have content and title
                                                    if (!$get('title') || !$get('body')) {
                                                        return null;
                                                    }

                                                    // Create a temporary Post object for preview
                                                    $tempPost = new Post([
                                                        'title'   => $get('title'),
                                                        'body'    => $get('body'),
                                                        'excerpt' => $get('excerpt'),
                                                    ]);

                                                    $aiService = app(AIContentService::class);
                                                    return $aiService->generateSocialPost($tempPost, 'twitter');
                                                }
                                            ])
                                            ->columnSpanFull()
                                            ->visible(
                                                fn ($get) => $get('auto_generate_social') &&
                                                in_array('twitter', $get('social_platforms') ?? []) &&
                                                $get('title') &&
                                                $get('body')
                                            ),
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
                    ->color(fn (string $state): string => match (strtoupper($state)) {
                        'DRAFT'     => 'warning',
                        'REVIEWING' => 'warning',
                        'PUBLISHED' => 'success',
                        default     => 'gray',
                    })
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->color(fn ($record) => $record->categories->count() > 0 ? $record->categories->first()->color : 'gray'),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->toggleable(),
                BooleanColumn::make('featured')
                    ->label('Featured')
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->datetime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->datetime()
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
                TableAction::make('social_share')
                    ->label('Share')
                    ->icon('heroicon-o-share')
                    ->color('warning')
                    ->url(fn (Post $record): string => route('filament.admin.resources.social-posts.create', ['post_id' => $record->id]))
                    ->visible(fn (Post $record): bool => $record->status === 'published')
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

    public static function getEloQuery(): Builder
    {
        return parent::getEloQuery()
            ->with(['user', 'categories']);
    }
}
