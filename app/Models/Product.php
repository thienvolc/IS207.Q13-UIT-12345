<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductMeta;
use App\Models\Category;
use App\Models\Tag;
use App\Models\CartItem;
use App\Models\OrderItem;

/**
 * Product Model
 *
 * @property int $product_id Primary key
 * @property int|null $category_id Main category foreign key
 * @property string $title Product title
 * @property string|null $meta_title SEO meta title
 * @property string $slug URL-friendly slug
 * @property string|null $thumb Thumbnail image path
 * @property string|null $desc Product description
 * @property string|null $summary Short summary
 * @property string|null $type Product type
 * @property string|null $sku Stock keeping unit
 * @property float $price Product price
 * @property int $quantity Available quantity
 * @property \Illuminate\Support\Carbon|null $published_at Publication date
 * @property int $status Product status (draft/published/archived)
 * @property float $discount Discount amount
 * @property \Illuminate\Support\Carbon|null $starts_at Discount start date
 * @property \Illuminate\Support\Carbon|null $ends_at Discount end date
 * @property int|null $created_by User who created this product
 * @property int|null $updated_by User who updated this product
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductMeta> $metas Product metadata
 * @property-read int|null $metas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $categories Product categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $tags Product tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CartItem> $cartItems Cart items containing this product
 * @property-read int|null $cart_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $orderItems Order items containing this product
 * @property-read int|null $order_items_count
 * @method static where(string $string, int $productId)
 * @method static create(array $data)
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
        'price'        => 'decimal:2',
        'discount'     => 'decimal:2',
        'quantity'     => 'integer',
        'status'       => 'integer',
        'published_at' => 'datetime',
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Relationships
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

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
}
