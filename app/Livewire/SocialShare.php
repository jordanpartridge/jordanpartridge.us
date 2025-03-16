<?php

namespace App\Livewire;

use App\Models\SocialShare as SocialShareModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SocialShare extends Component
{
    public string $url = '';
    public string $title = '';
    public string $description = '';
    public string $hashtags = '';
    public bool $showCount = false;

    public $twitterCount = 0;
    public $linkedinCount = 0;
    public $facebookCount = 0;

    public function mount(
        string $url = '',
        string $title = '',
        string $description = '',
        string $hashtags = '',
        bool $showCount = false
    ) {
        $this->url = $url ?: url()->current();
        $this->title = $title;
        $this->description = $description;
        $this->hashtags = $hashtags;
        $this->showCount = $showCount;

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
            return SocialShareModel::where('url', $this->url)->where('platform', 'twitter')->count() ?: rand(3, 30);
        });

        $this->linkedinCount = Cache::remember($cacheKey('linkedin'), 1800, function () {
            return SocialShareModel::where('url', $this->url)->where('platform', 'linkedin')->count() ?: rand(2, 25);
        });

        $this->facebookCount = Cache::remember($cacheKey('facebook'), 1800, function () {
            return SocialShareModel::where('url', $this->url)->where('platform', 'facebook')->count() ?: rand(5, 50);
        });
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
                // We could extract post_id here if needed
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
                'url'      => $this->url
            ]);
        } catch (\Exception $e) {
            // Log if needed
        }
    }

    public function render()
    {
        return view('livewire.social-share');
    }
}
