<?php

namespace App\Domains\Cart\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $cart_id
 * @property mixed $items
 * @property mixed $first_name
 * @property mixed $middle_name
 * @property mixed $last_name
 * @property mixed $phone
 * @property mixed $email
 * @property mixed $line1
 * @property mixed $line2
 * @property mixed $city
 * @property mixed $province
 * @property mixed $country
 * @property mixed $note
 * @property mixed $user_id
 * @property mixed $updated_at
 * @property mixed $status
 * @method static create(array $array)
 * @method static where(string $string, int $cartId)
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
        return $this->belongsTo(\App\Domains\Identity\Entities\User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Domains\Cart\Entities\CartItem::class, 'cart_id', 'cart_id');
    }
}
