<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
