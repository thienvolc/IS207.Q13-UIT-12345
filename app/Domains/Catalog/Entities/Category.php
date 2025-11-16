<?php

namespace App\Domains\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    protected $table = 'categories';

    protected $fillable = [
        'parent_id', 'level',
        'title', 'meta_title', 'slug', 'desc',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'category_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
