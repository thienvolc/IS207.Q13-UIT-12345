<?php

namespace App\Domains\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $primaryKey = 'meta_id';
    protected $table = 'product_metas';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'key', 'content',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
