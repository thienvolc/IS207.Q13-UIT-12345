<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
