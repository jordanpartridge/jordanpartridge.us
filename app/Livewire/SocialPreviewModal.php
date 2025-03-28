<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\AI\AIContentService;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class SocialPreviewModal extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $title;
    public $body;
    public $excerpt;
    public $linkedinContent = '';
    public $twitterContent = '';
    public $isGenerating = false;

    public function mount($title, $body, $excerpt = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->excerpt = $excerpt;

        $this->generatePreviews();
    }

    public function generatePreviews()
    {
        if (empty($this->title) || empty($this->body)) {
            Notification::make()
                ->title('Cannot generate previews')
                ->body('Title and content are required to generate previews')
                ->warning()
                ->send();
            return;
        }

        $this->isGenerating = true;

        // Create a temporary Post object
        $tempPost = new Post([
            'title'   => $this->title,
            'body'    => $this->body,
            'excerpt' => $this->excerpt,
        ]);

        try {
            $aiService = app(AIContentService::class);

            // Generate content for each platform
            $this->linkedinContent = $aiService->generateSocialPost($tempPost, 'linkedin');
            $this->twitterContent = $aiService->generateSocialPost($tempPost, 'twitter');

            Notification::make()
                ->title('Social previews generated successfully')
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->linkedinContent = 'Error generating preview: ' . $e->getMessage();
            $this->twitterContent = 'Error generating preview: ' . $e->getMessage();

            Notification::make()
                ->title('Failed to generate social previews')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    public function regenerateAction(): Action
    {
        return Action::make('regenerate')
            ->label('Regenerate')
            ->color('primary')
            ->icon('heroicon-o-sparkles')
            ->disabled(fn () => $this->isGenerating)
            ->action(function () {
                $this->generatePreviews();
            });
    }

    public function closeAction(): Action
    {
        return Action::make('close')
            ->label('Close')
            ->color('gray')
            ->action(function () {
                $this->dispatch('close-modal');
            });
    }

    public function render()
    {
        return view('livewire.social-preview-modal');
    }
}
