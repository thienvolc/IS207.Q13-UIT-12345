<?php

namespace App\Domains\Catalog\DTOs\Category\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class CategoryDTO implements BaseDTO
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
        /** @var CategoryDTO[] */
        public array   $children = [],
    ) {}

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
