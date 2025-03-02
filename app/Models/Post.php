<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasUuids, HasSlug;

    const TYPE_POST = 'post';
    const TYPE_PAGE = 'page';

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SCHEDULED = 'scheduled';

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'excerpt',
        'type',
        'status',
        'featured_image',
        'user_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'schema_markup',
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
        return $query->orderBy('created_at', 'DESC')
            ->paginate(12);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeExcludeFeatured($query, $featured): mixed
    {
        return $query->where('id', '!=', $featured);
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
}
