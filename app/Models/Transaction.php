<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Transaction Model
 *
 * @property int $transaction_id Primary key
 * @property int $order_id Foreign key to orders
 * @property float $amount Transaction amount
 * @property string|null $content Transaction description/content
 * @property string|null $code Transaction reference code
 * @property int $type Transaction type (payment/refund)
 * @property string|null $mode Payment mode (cash/card/transfer)
 * @property int $status Transaction status (pending/completed/failed)
 * @property int $version Optimistic locking version
 * @property int|null $created_by User who created this transaction
 * @property int|null $updated_by User who updated this transaction
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Order|null $order Associated order
 */
class Transaction extends Model
{
    protected $primaryKey = 'transaction_id';
    protected $table = 'transactions';

    protected $fillable = [
        'order_id', 'amount', 'content', 'code', 'type', 'mode', 'status',
        'version',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'type'       => 'integer',
        'status'     => 'integer',
        'version'    => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
