<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CartItem Model
 *
 * @property int $cart_item_id Primary key
 * @property int $cart_id Foreign key to carts
 * @property int $product_id Foreign key to products
 * @property string|null $sku Product SKU snapshot
 * @property bool $is_active Item active flag
 * @property float $price Price snapshot at time of adding
 * @property int $quantity Item quantity
 * @property float $discount Discount snapshot
 * @property string|null $note Item note
 *
 * @property-read \App\Models\Cart $cart Associated cart
 * @property-read \App\Models\Product $product Associated product
 * @method static where(string $string, int $cartId)
 */
class CartItem extends Model
{
    protected $primaryKey = 'cart_item_id';
    protected $table = 'cart_items';
    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'sku',
        'is_active',
        'price',
        'quantity',
        'discount',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'    => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
