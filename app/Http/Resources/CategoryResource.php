<?php

namespace App\Http\Resources;

class CategoryResource
{
    public static function transform($category): array
    {
        return [
            'category_id' => $category->category_id,
            'parent_id' => $category->parent_id,
            'level' => $category->level,
            'title' => $category->title,
            'meta_title' => $category->meta_title,
            'slug' => $category->slug,
            'desc' => $category->desc,
            'created_at' => $category->created_at?->toIso8601String(),
            'updated_at' => $category->updated_at?->toIso8601String(),
            'created_by' => $category->created_by,
            'updated_by' => $category->updated_by,
        ];
    }

    public static function transformWithChildren($category): array
    {
        $data = self::transform($category);

        if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
            $data['children'] = $category->children->map(fn($child) => self::transformWithChildren($child))->toArray();
        } else {
            $data['children'] = [];
        }

        return $data;
    }

    public static function collection($categories): array
    {
        return $categories->map(fn($category) => self::transform($category))->toArray();
    }

    public static function collectionWithChildren($categories): array
    {
        return $categories->map(fn($category) => self::transformWithChildren($category))->toArray();
    }
}
