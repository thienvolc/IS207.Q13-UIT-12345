<?php

namespace App\Domains\Sales\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereHas(string $string, \Closure $param)
 * @method static create(array $data)
 * @method static where(string $string, int $cartId)
 * @method static whereIn(string $string, array $itemIds)
 * @method static insert(mixed[] $toArray)
 * @property mixed $product_id
 * @property mixed $cart_id
 * @property mixed $product
 * @property mixed $quantity
 * @property mixed $price
 * @property mixed $discount
 * @property mixed $note
 * @property mixed $cart_item_id
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
        return $this->belongsTo(\App\Domains\Sales\Entities\Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Domains\Catalog\Entities\Product::class, 'product_id', 'product_id');
    }
}
