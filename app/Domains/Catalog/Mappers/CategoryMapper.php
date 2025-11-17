<?php

namespace App\Domains\Catalog\Mappers;

use App\Domains\Catalog\DTOs\Category\Responses\CategoryDTO;
use App\Domains\Catalog\DTOs\Category\Responses\PublicCategoryDTO;
use App\Domains\Catalog\Entities\Category;
use Illuminate\Database\Eloquent\Collection;

readonly class CategoryMapper
{
    public function toDTO(Category $category): CategoryDTO
    {
        $children = [];
        if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
            $children = $category->children->map(fn($c) => $this->toDTO($c))->all();
        }

        return new CategoryDTO(
            categoryId: $category->category_id,
            parentId: $category->parent_id,
            level: $category->level,
            title: $category->title,
            metaTitle: $category->meta_title,
            slug: $category->slug,
            desc: $category->desc,
            createdAt: $category->created_at?->toIso8601String(),
            updatedAt: $category->updated_at?->toIso8601String(),
            createdBy: $category->created_by,
            updatedBy: $category->updated_by,
            children: $children,
        );
    }

    public function toPublicDTO(Category $category): PublicCategoryDTO
    {
        $children = [];
        if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
            $children = $category->children
                ->map(fn($c) => $this->toPublicDTO($c))
                ->values()
                ->all();
        }

        return new PublicCategoryDTO(
            categoryId: $category->category_id,
            parentId: $category->parent_id,
            level: $category->level,
            title: $category->title,
            slug: $category->slug,
            desc: $category->desc,
            children: $children,
        );
    }

    /**
     * @param Collection<int, Category> $categories
     * @return CategoryDTO[]
     */
    public function toDTOs(Collection $categories): array
    {
        // TODO: Optimize to prevent N+1 problem
        return $categories->map(fn($category) => $this->toDTO($category))->all();
    }

    /**
     * @param Collection<int, Category> $categories
     * @return PublicCategoryDTO[]
     */
    public function toPublicDTOs(Collection $categories): array
    {
        // TODO: Optimize to prevent N+1 problem
        return $categories->map(fn($category) => $this->toPublicDTO($category))->all();
    }
}
