<?php

namespace App\Domains\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $tagId)
 * @method static count()
 * @method static create(array $data)
 * @property mixed $tag_id
 * @property mixed $title
 * @property mixed $meta_title
 * @property mixed $slug
 * @property mixed $desc
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_by
 * @property mixed $updated_by
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
