<?php

namespace App\Domains\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $blogpost_id
 * @property string $title
 * @property string|null $meta_title
 * @property string $slug
 * @property string|null $thumb
 * @property string|null $summary
 * @property string|null $content
 * @property string|null $conclusion
 * @property int $status
 * @property Carbon|null $published_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BlogPost extends Model
{
    protected $primaryKey = 'blogpost_id';
    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'meta_title',
        'slug',
        'thumb',
        'summary',
        'content',
        'conclusion',
        'status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
