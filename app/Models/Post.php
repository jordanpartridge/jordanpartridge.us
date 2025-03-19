<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory;
    use HasSlug;

    public const TYPE_POST = 'post';
    public const TYPE_PAGE = 'page';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'type',
        'status',
        'image',
        'user_id',
        'active',
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_schema',
        'meta_data',
        'description',
        'content'
    ];

    protected $casts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function featured($featured): mixed
    {
        return $featured?->id ?? 0;
    }

    public function scopeTypePost($query): void
    {
        $query->where('type', 'post');
    }

    public function scopePublished($query): void
    {
        $query->where('status', Post::STATUS_PUBLISHED);
    }

    /**
     * @return mixed
     */
    public function scopeList($query): mixed
    {
        return $query->published()
            ->typePost()
            ->excludeFeatured()
            ->orderBy('created_at', 'DESC');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeExcludeFeatured($query, $featured = null): mixed
    {
        return $query->where('featured', false);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the social shares for this post.
     */
    public function socialShares(): HasMany
    {
        return $this->hasMany(SocialShare::class);
    }

    /**
     * Get the share count for a specific platform
     */
    public function getShareCount(string $platform = null): int
    {
        $query = $this->socialShares();

        if ($platform) {
            $query->where('platform', $platform);
        }

        return $query->count();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the URL to the post.
     */
    public function route(): string
    {
        return url("/blog/{$this->slug}");
    }
}
