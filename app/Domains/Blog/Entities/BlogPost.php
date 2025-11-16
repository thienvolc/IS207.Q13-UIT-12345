<?php

namespace App\Domains\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * BlogPost Model
 *
 * @property int $blogpost_id Primary key
 * @property string $title Blog post title
 * @property string|null $meta_title SEO meta title
 * @property string $slug URL-friendly slug
 * @property string|null $thumb Thumbnail image path
 * @property string|null $summary Post summary/excerpt
 * @property string|null $content Post content
 * @property string|null $conclusion Post conclusion
 * @property int $status Post status (draft/published/archived)
 * @property int|null $created_by User who created this post
 * @property int|null $updated_by User who updated this post
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BlogPost extends Model
{
    protected $primaryKey = 'blogpost_id';
    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'meta_title', 'slug', 'thumb',
        'summary', 'content', 'conclusion',
        'status',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
