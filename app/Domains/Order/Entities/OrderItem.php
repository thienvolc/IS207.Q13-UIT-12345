<?php

namespace App\Domains\Order\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 * @property mixed $order_item_id
 * @property mixed $product_id
 * @property mixed $price
 * @property mixed $quantity
 * @property mixed $discount
 * @property mixed $note
 * @property mixed $product
 */
class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';
    protected $table = 'order_items';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
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

    public function order()
    {
        return $this->belongsTo(\App\Domains\Order\Entities\Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Domains\Catalog\Entities\Product::class, 'product_id', 'product_id');
    }
}
