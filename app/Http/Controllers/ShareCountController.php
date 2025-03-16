<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SocialShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShareCountController extends Controller
{
    /**
     * Get social media share counts for a URL
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount(Request $request)
    {
        $request->validate([
            'url'      => 'required|url',
            'platform' => 'required|string|in:twitter,linkedin,facebook',
        ]);

        $url = $request->input('url');
        $platform = $request->input('platform');

        // Cache key based on URL and platform
        $cacheKey = "share_count:{$platform}:" . md5($url);

        // Return cached count if available (cache for 30 minutes)
        return response()->json([
            'count' => Cache::remember($cacheKey, 1800, function () use ($url, $platform) {
                return $this->fetchShareCount($url, $platform);
            })
        ]);
    }

    /**
     * Track share of content
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackShare(Request $request)
    {
        $request->validate([
            'url'      => 'required|url',
            'platform' => 'required|string',
        ]);

        $url = $request->input('url');
        $platform = $request->input('platform');

        // Try to find a post id from the URL
        $postId = null;
        $path = parse_url($url, PHP_URL_PATH);

        if ($path && Str::contains($path, '/blog/')) {
            $slug = Str::afterLast($path, '/');
            $post = Post::where('slug', $slug)->first();
            if ($post) {
                $postId = $post->id;
            }
        }

        // Record the share in the database
        SocialShare::create([
            'url'        => $url,
            'platform'   => $platform,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id'    => Auth::id(),
            'post_id'    => $postId,
        ]);

        // Log the share event
        Log::info('Content shared', [
            'url'        => $url,
            'platform'   => $platform,
            'user_agent' => $request->userAgent(),
            'ip'         => $request->ip(),
            'post_id'    => $postId,
        ]);

        // Increment share count in cache for quick retrieval
        $cacheKey = "share_count:{$platform}:" . md5($url);
        $currentCount = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $currentCount + 1, 86400); // Store for 24 hours

        return response()->json(['success' => true]);
    }

    /**
     * Fetch share count from various platforms
     *
     * @param string $url
     * @param string $platform
     * @return int
     */
    private function fetchShareCount(string $url, string $platform): int
    {
        try {
            // Get actual count from our database
            $count = SocialShare::where('url', $url)
                ->where('platform', $platform)
                ->count();

            // If we have data, return it
            if ($count > 0) {
                return $count;
            }

            // Otherwise fall back to API-based or mock counts
            switch ($platform) {
                case 'facebook':
                    // Facebook requires a Graph API token for accurate counts
                    // This is a simplified implementation
                    return rand(5, 50); // Mock data for demo purposes

                case 'twitter':
                    // Twitter no longer provides a public API for share counts
                    return rand(3, 30); // Mock data for demo purposes

                case 'linkedin':
                    // LinkedIn no longer provides share counts via public API
                    return rand(2, 25); // Mock data for demo purposes

                default:
                    return 0;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching share count', [
                'url'      => $url,
                'platform' => $platform,
                'error'    => $e->getMessage()
            ]);

            return 0;
        }
    }
}
