<?php

namespace App\Domains\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $transaction_id
 * @property mixed $order_id
 * @property mixed $amount
 * @property mixed $content
 * @property mixed $code
 * @property mixed $type
 * @property mixed $mode
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $order
 * @method static create(array $data)
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
        return $this->belongsTo(\App\Domains\Order\Entities\Order::class, 'order_id', 'order_id');
    }
}
