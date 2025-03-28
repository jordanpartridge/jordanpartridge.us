<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('social_share')
                ->label('Share to Social')
                ->icon('heroicon-o-share')
                ->color('warning')
                ->action(function () {
                    $post = $this->record;

                    // Only allow for published posts
                    if ($post->status !== 'published') {
                        Notification::make()
                            ->title('Post not published')
                            ->body('Only published posts can be shared to social media')
                            ->warning()
                            ->send();
                        return;
                    }

                    // Redirect to the social media creation page with the post pre-selected
                    redirect()->route('filament.admin.resources.social-posts.create', [
                        'post_id' => $post->id
                    ]);
                })
                ->visible(fn () => $this->record->status === 'published'),
        ];
    }
}
