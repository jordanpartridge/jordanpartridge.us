<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory;
    use HasSlug;
    use LogsActivity;
    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_PUBLISHED = 'PUBLISHED';

    public const TYPE_POST = 'post';

    public const TYPE_PAGE = 'page';

    protected $fillable = [
        'title',
        'body',
        'status',
        'slug',
        'image',
        'type',
        'featured',
        'user_id',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_schema',
        'meta_data',
    ];

    public function scopeExcludeFeatured($query): void
    {
        $featured = Post::where('featured', 1)->where('type', 'post')->first();
        $query->where('id', '!=', ($featured->id) ?? 0);
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
            ->excludeFeatured()
            ->typePost()
            ->published();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
                    ->withTimestamps();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('system');
    }
}