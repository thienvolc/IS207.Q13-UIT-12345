<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OrderItem Model
 *
 * @property int $order_item_id Primary key
 * @property int $order_id Foreign key to orders
 * @property int $product_id Foreign key to products
 * @property string|null $sku Product SKU snapshot
 * @property bool $is_active Item active flag
 * @property float $price Price snapshot at time of order
 * @property int $quantity Item quantity
 * @property float $discount Discount snapshot
 * @property string|null $note Item note
 *
 * @property-read \App\Models\Order $order Associated order
 * @property-read \App\Models\Product $product Associated product
 */
class OrderItem extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
