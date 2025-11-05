<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'thumb',
        'desc',
        'summary',
        'type',
        'price',
        'quantity',
        'status',
        'discount',
        'ends_at'
    ];

    protected $casts = [
        'ends_at'  => 'datetime',
        'price'    => 'decimal:2',
        'discount' => 'decimal:2',
    ];
}

