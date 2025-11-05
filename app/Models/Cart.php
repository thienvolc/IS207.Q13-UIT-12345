<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'first_name', 'middle_name', 'last_name',
        'phone', 'email',
        'line1', 'line2', 'city', 'province', 'country',
        'status', 'note',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}
