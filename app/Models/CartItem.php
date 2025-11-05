<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
