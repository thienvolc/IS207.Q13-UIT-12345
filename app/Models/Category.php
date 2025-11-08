<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Category Model
 *
 * @property int $category_id Primary key
 * @property int|null $parent_id Parent category foreign key
 * @property int $level Category hierarchy level
 * @property string $title Category title
 * @property string|null $meta_title SEO meta title
 * @property string $slug URL-friendly slug
 * @property string|null $desc Category description
 * @property int|null $created_by User who created this category
 * @property int|null $updated_by User who updated this category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Category|null $parent Parent category
 * @property-read Collection<int, Category> $children Child categories
 * @property-read int|null $children_count
 * @property-read Collection<int, Product> $products Products in this category
 * @property-read int|null $products_count
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
        'level'      => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
