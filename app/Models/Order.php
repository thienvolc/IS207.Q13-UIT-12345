<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'subtotal', 'tax', 'shipping', 'total',
        'discount_total', 'promo', 'discount', 'grand_total',
        'first_name', 'middle_name', 'last_name',
        'phone', 'email',
        'line1', 'line2', 'city', 'province', 'country',
        'orders_at', 'status', 'note',
        'version',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'tax'            => 'decimal:2',
        'shipping'       => 'decimal:2',
        'total'          => 'decimal:2',
        'discount_total' => 'decimal:2',
        'discount'       => 'decimal:2',
        'grand_total'    => 'decimal:2',
        'status'         => 'integer',
        'version'        => 'integer',
        'orders_at'      => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id', 'order_id');
    }
}
