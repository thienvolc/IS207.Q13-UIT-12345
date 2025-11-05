<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $primaryKey = 'tag_id';
    protected $table = 'tags';

    protected $fillable = [
        'title', 'meta_title', 'slug', 'desc',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }
}
