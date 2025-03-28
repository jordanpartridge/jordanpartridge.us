<?php

namespace App\Filament\Resources\PostResource\Modals;

use App\Models\Post;
use App\Services\AI\AIContentService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class SocialPreviewModal extends Component implements HasForms
{
    use InteractsWithForms;

    public $linkedinContent = '';
    public $twitterContent = '';
    public $postData = [];

    public function mount($title, $body, $excerpt = null)
    {
        $this->postData = [
            'title'   => $title,
            'body'    => $body,
            'excerpt' => $excerpt,
        ];

        $this->generatePreviews();
    }

    public function generatePreviews()
    {
        // Create a temporary Post object
        $tempPost = new Post([
            'title'   => $this->postData['title'],
            'body'    => $this->postData['body'],
            'excerpt' => $this->postData['excerpt'],
        ]);

        try {
            $aiService = app(AIContentService::class);
            $this->linkedinContent = $aiService->generateSocialPost($tempPost, 'linkedin');
            $this->twitterContent = $aiService->generateSocialPost($tempPost, 'twitter');
        } catch (\Exception $e) {
            $this->linkedinContent = 'Error generating preview: ' . $e->getMessage();
            $this->twitterContent = 'Error generating preview: ' . $e->getMessage();
        }
    }

    public function render(): View
    {
        return view('filament.resources.post-resource.modals.social-preview-modal');
    }
}
