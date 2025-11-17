<?php

namespace App\Domains\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereNull(string $string)
 * @method static where(string $string, string $slug)
 * @method static create(array $data)
 * @property mixed $children
 * @property mixed $category_id
 * @property mixed $parent_id
 * @property mixed $level
 * @property mixed $title
 * @property mixed $slug
 * @property mixed $desc
 * @property mixed $meta_title
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_by
 * @property mixed $updated_by
 */
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
