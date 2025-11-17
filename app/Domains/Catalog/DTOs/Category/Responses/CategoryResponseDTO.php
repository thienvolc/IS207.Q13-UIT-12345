<?php

namespace App\Domains\Catalog\DTOs\Category\Responses;

use App\Domains\Catalog\Entities\Category;
use App\Domains\Common\DTOs\BaseDTO;
use Illuminate\Database\Eloquent\Collection;

readonly class CategoryResponseDTO implements BaseDTO
{
    public function __construct(
        public int     $categoryId,
        public ?int    $parentId,
        public int     $level,
        public string  $title,
        public ?string $metaTitle,
        public string  $slug,
        public ?string $desc,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?int    $createdBy = null,
        public ?int    $updatedBy = null,
        /** @var CategoryResponseDTO[] */
        public array   $children = [],
    ) {}

    public static function fromModel($category): self
    {
        $children = [];
        if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
            $children = $category->children->map(fn($c) => self::fromModel($c))->all();
        }

        return new self(
            categoryId: $category->category_id,
            parentId: $category->parent_id,
            level: $category->level,
            title: $category->title,
            metaTitle: $category->meta_title,
            slug: $category->slug,
            desc: $category->desc,
            createdAt: optional($category->created_at)?->toIso8601String(),
            updatedAt: optional($category->updated_at)?->toIso8601String(),
            createdBy: $category->created_by,
            updatedBy: $category->updated_by,
            children: $children,
        );
    }

    /**
     * @param Collection<int, Category> $categories
     * @return CategoryResponseDTO[]
     */
    public static function collectionWithChildren(Collection $categories): array
    {
        return $categories->map(fn($category) => self::fromModel($category)->toArray())->toArray();
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'parent_id' => $this->parentId,
            'level' => $this->level,
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'children' => array_map(fn($c) => $c->toArray(), $this->children),
        ];
    }
}
