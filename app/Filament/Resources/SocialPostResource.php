<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialPostResource\Pages;
use App\Models\Post;
use App\Services\AI\AIContentService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SocialPostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?string $navigationLabel = 'Social Media';
    
    protected static ?int $navigationSort = 30;

    public static function getNavigationBadge(): ?string
    {
        return Post::where('published_at', '>=', now()->subDays(7))->count();
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id')
                    ->label('Post')
                    ->options(
                        Post::published()->pluck('title', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $post = Post::find($state);
                            if ($post) {
                                $set('post_title', $post->title);
                                $set('post_description', $post->description);
                            }
                        }
                    }),
                    
                TextInput::make('post_title')
                    ->label('Title')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpan(2),
                    
                Textarea::make('post_description')
                    ->label('Description')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpan(2),
                    
                Section::make('Social Media')
                    ->schema([
                        Toggle::make('auto_share')
                            ->label('Automatically share on social platforms')
                            ->default(true),
                        
                        CheckboxList::make('social_platforms')
                            ->label('Share Platforms')
                            ->options([
                                'linkedin' => 'LinkedIn',
                                'twitter' => 'Twitter/X'
                            ])
                            ->default(['linkedin', 'twitter'])
                            ->columns(2),
                            
                        ViewField::make('linkedin_preview')
                            ->view('filament.forms.components.social-preview', [
                                'platform' => 'linkedin',
                                'renderCallback' => function ($get) {
                                    $postId = $get('id');
                                    if (!$postId) return null;
                                    
                                    $post = Post::find($postId);
                                    if (!$post) return null;
                                    
                                    $aiService = app(AIContentService::class);
                                    return $aiService->generateSocialPost($post, 'linkedin');
                                }
                            ])
                            ->columnSpan(2)
                            ->visible(fn ($get) => in_array('linkedin', $get('social_platforms') ?? [])),
                            
                        ViewField::make('twitter_preview')
                            ->view('filament.forms.components.social-preview', [
                                'platform' => 'twitter',
                                'renderCallback' => function ($get) {
                                    $postId = $get('id');
                                    if (!$postId) return null;
                                    
                                    $post = Post::find($postId);
                                    if (!$post) return null;
                                    
                                    $aiService = app(AIContentService::class);
                                    return $aiService->generateSocialPost($post, 'twitter');
                                }
                            ])
                            ->columnSpan(2)
                            ->visible(fn ($get) => in_array('twitter', $get('social_platforms') ?? [])),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                    
                IconColumn::make('social_media_shared')
                    ->label('Shared')
                    ->boolean()
                    ->default(false),
            ])
            ->filters([
                Filter::make('published')
                    ->query(fn ($query) => $query->whereNotNull('published_at')),
                    
                Filter::make('not_shared')
                    ->label('Not shared on social media')
                    ->query(function ($query) {
                        // This is a placeholder - in a real implementation we would
                        // filter based on a relation or metadata field
                        return $query;
                    }),
            ])
            ->actions([
                Action::make('share')
                    ->label('Share Now')
                    ->icon('heroicon-o-share')
                    ->action(function (Post $record) {
                        // TODO: Implement actual sharing functionality
                        // This is where we would call the SocialMediaService
                    }),
                    
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('share_selected')
                    ->label('Share Selected')
                    ->icon('heroicon-o-share')
                    ->action(function (array $records) {
                        // TODO: Implement actual bulk sharing functionality
                    }),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialPosts::route('/'),
            'create' => Pages\CreateSocialPost::route('/create'),
        ];
    }
}
