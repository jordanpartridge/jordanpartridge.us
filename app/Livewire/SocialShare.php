<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\SocialShare as SocialShareModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class SocialShare extends Component
{
    public string $url = '';
    public string $title = '';
    public string $description = '';
    public string $hashtags = '';
    public bool $showCount = false;
    public ?int $postId = null;

    public $twitterCount = 0;
    public $linkedinCount = 0;
    public $facebookCount = 0;

    public function mount(
        string $url = '',
        string $title = '',
        string $description = '',
        string $hashtags = '',
        bool $showCount = false,
        ?int $postId = null
    ) {
        $this->url = $url ?: url()->current();
        $this->title = $title;
        $this->description = $description;
        $this->hashtags = $hashtags;
        $this->showCount = $showCount;
        $this->postId = $postId;

        // If postId is not directly provided, try to extract it from the URL
        if (!$this->postId) {
            $this->extractPostIdFromUrl();
        }

        if ($showCount) {
            $this->loadCounts();
        }
    }

    public function loadCounts()
    {
        $cacheKey = function ($platform) {
            return "share_count:{$platform}:" . md5($this->url);
        };

        $this->twitterCount = Cache::remember($cacheKey('twitter'), 1800, function () {
            return SocialShareModel::where('url', $this->url)->where('platform', 'twitter')->count();
        });

        $this->linkedinCount = Cache::remember($cacheKey('linkedin'), 1800, function () {
            return SocialShareModel::where('url', $this->url)->where('platform', 'linkedin')->count();
        });

        $this->facebookCount = Cache::remember($cacheKey('facebook'), 1800, function () {
            return SocialShareModel::where('url', $this->url)->where('platform', 'facebook')->count();
        });
    }

    /**
     * Get sharing URL for a specific platform with properly pre-populated content
     *
     * @param string $platform Platform name (twitter, linkedin, facebook)
     * @return string The sharing URL
     */
    public function getSharingUrl(string $platform): string
    {
        $encodedUrl = urlencode($this->url);
        $encodedTitle = urlencode($this->title);

        switch ($platform) {
            case 'twitter':
                return "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}&hashtags={$this->hashtags}";

            case 'linkedin':
                // Pre-populate the post text with the description for LinkedIn
                $encodedDescription = urlencode($this->description);
                return "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}&summary={$encodedDescription}";

            case 'facebook':
                return "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}";

            default:
                return $this->url;
        }
    }

    public function trackShare($platform)
    {
        try {
            // Record the share
            SocialShareModel::create([
                'url'        => $this->url,
                'platform'   => $platform,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id'    => Auth::id(),
                'post_id'    => $this->postId,
            ]);

            // Increment the count in cache
            $cacheKey = "share_count:{$platform}:" . md5($this->url);
            $currentCount = Cache::get($cacheKey, 0);
            Cache::put($cacheKey, $currentCount + 1, 86400);

            // Update the UI count
            if ($platform === 'twitter') {
                $this->twitterCount++;
            } elseif ($platform === 'linkedin') {
                $this->linkedinCount++;
            } elseif ($platform === 'facebook') {
                $this->facebookCount++;
            }

            // Track with any analytics
            $this->dispatch('analytics', [
                'platform' => $platform,
                'url'      => $this->url,
                'post_id'  => $this->postId
            ]);
        } catch (\Exception $e) {
            // Log if needed
        }
    }

    public function render()
    {
        return view('livewire.social-share');
    }

    /**
     * Extract post ID from URL if it's a blog post URL
     */
    protected function extractPostIdFromUrl()
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        if ($path && Str::contains($path, '/blog/')) {
            $slug = Str::afterLast($path, '/');
            $post = Post::where('slug', $slug)->first();
            if ($post) {
                $this->postId = $post->id;
            }
        }
    }
}
