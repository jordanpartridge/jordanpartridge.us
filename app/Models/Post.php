<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

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
    public function scopeList($query)
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
}
