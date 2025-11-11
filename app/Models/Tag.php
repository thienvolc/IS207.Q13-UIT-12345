<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tag Model
 *
 * @property int $tag_id Primary key
 * @property string $title Tag title
 * @property string|null $meta_title SEO meta title
 * @property string $slug URL-friendly slug
 * @property string|null $desc Tag description
 * @property int|null $created_by User who created this tag
 * @property int|null $updated_by User who updated this tag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products Products with this tag
 * @property-read int|null $products_count
 */
class Tag extends Model
{
    protected $primaryKey = 'tag_id';
    protected $table = 'tags';

    protected $fillable = [
        'title', 'meta_title', 'slug', 'desc',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }
}
