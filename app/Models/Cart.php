<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cart Model
 *
 * @property int $cart_id Primary key
 * @property int $user_id Foreign key to users
 * @property string|null $first_name Shipping first name
 * @property string|null $middle_name Shipping middle name
 * @property string|null $last_name Shipping last name
 * @property string|null $phone Shipping phone
 * @property string|null $email Shipping email
 * @property string|null $line1 Address line 1
 * @property string|null $line2 Address line 2
 * @property string|null $city City
 * @property string|null $province Province/State
 * @property string|null $country Country
 * @property int $status Cart status (active/completed/cancelled)
 * @property string|null $note Cart note
 * @property int|null $created_by User who created this cart
 * @property int|null $updated_by User who updated this cart
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user Cart owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $items Cart items
 * @property-read int|null $items_count
 */
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
