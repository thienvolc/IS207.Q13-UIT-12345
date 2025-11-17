<?php

namespace App\Domains\Catalog\DTOs\Category\Responses;

use App\Domains\Catalog\Entities\Category;
use App\Domains\Common\DTOs\BaseDTO;
use Illuminate\Database\Eloquent\Collection;

readonly class CategoryPublicResponseDTO implements BaseDTO
{
    public function __construct(
        public int     $categoryId,
        public ?int    $parentId,
        public int     $level,
        public string  $title,
        public string  $slug,
        public ?string $desc = null,
        /** @var CategoryPublicResponseDTO[] */
        public array   $children = [],
    ) {}

    public static function fromModel(Category $category): self
    {
        $children = [];
        if ($category->relationLoaded('children') && $category->children->isNotEmpty()) {
            $children = $category->children
                ->map(fn($c) => self::fromModel($c))
                ->values()
                ->all();
        }

        return new self(
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
     * @return CategoryPublicResponseDTO[]
     */
    public static function collection(Collection $categories): array
    {
        return $categories->map(fn($category) => self::fromModel($category))->all();
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'parent_id' => $this->parentId,
            'level' => $this->level,
            'title' => $this->title,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'children' => array_map(fn($c) => $c->toArray(), $this->children),
        ];
    }
}
