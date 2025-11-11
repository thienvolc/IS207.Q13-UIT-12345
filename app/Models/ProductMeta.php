<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ProductMeta Model
 *
 * @property int $meta_id Primary key
 * @property int $product_id Foreign key to products
 * @property string $key Meta key
 * @property string|null $content Meta content/value
 *
 * @property-read \App\Models\Product|null $product Associated product
 */
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
