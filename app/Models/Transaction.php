<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
