<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductMeta;
use App\Models\Category;
use App\Models\Tag;

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
