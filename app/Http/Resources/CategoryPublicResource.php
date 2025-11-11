<?php

namespace App\Http\Resources;

class CategoryPublicResource
{
    public static function transform($category): array
    {
        return [
            'category_id' => $category->category_id,
            'parent_id' => $category->parent_id,
            'level' => $category->level,
            'title' => $category->title,
            'slug' => $category->slug,
            'desc' => $category->desc,
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
