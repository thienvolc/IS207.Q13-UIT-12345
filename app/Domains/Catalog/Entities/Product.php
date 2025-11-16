<?php

namespace App\Domains\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $productId)
 * @method static whereIn(string $string, array $productIds)
 * @property mixed $quantity
 * @property mixed $price
 * @property mixed $product_id
 * @property mixed $status
 * @property mixed $title
 */
class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'title',
        'meta_title',
        'slug',
        'thumb',
        'desc',
        'summary',
        'type',
        'sku',
        'price',
        'quantity',
        'published_at',
        'status',
        'discount',
        'starts_at',
        'ends_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'integer',
        'status' => 'integer',
        'published_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships - reference domain entities for relations
    public function metas()
    {
        return $this->hasMany(ProductMeta::class, 'product_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
}
