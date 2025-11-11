<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order Model
 *
 * @property int $order_id Primary key
 * @property int $user_id Foreign key to users
 * @property float $subtotal Subtotal before tax and shipping
 * @property float $tax Tax amount
 * @property float $shipping Shipping cost
 * @property float $total Total before discount
 * @property float $discount_total Total discount amount
 * @property string|null $promo Promo code applied
 * @property float $discount Discount percentage
 * @property float $grand_total Final total amount
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
 * @property \Illuminate\Support\Carbon|null $orders_at Order placement timestamp
 * @property int $status Order status (pending/processing/completed/cancelled)
 * @property string|null $note Order note
 * @property int $version Optimistic locking version
 * @property int|null $created_by User who created this order
 * @property int|null $updated_by User who updated this order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user Order owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items Order items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions Payment transactions
 * @property-read int|null $transactions_count
 * @method static find(int $orderId)
 * @method static where(string $string, int $orderId)
 * @method static create(array $data)
 */
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
