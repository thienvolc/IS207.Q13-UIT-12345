<?php

namespace App\Domains\Catalog\Entities;

use App\Domains\Order\Entities\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, int $productId)
 * @method static whereIn(string $string, array $productIds)
 * @method static create(array $data)
 * @property mixed $quantity
 * @property mixed $price
 * @property mixed $product_id
 * @property mixed $status
 * @property mixed $title
 * @property mixed $published_at
 * @property mixed $categories
 * @property mixed $tags
 * @property mixed $meta_title
 * @property mixed $slug
 */
class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $table = 'products';

    protected $fillable = [
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

    public function metas(): HasMany
    {
        return $this->hasMany(ProductMeta::class, 'product_id', 'product_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public  function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
}
