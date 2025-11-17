<?php

namespace App\Domains\Catalog\DTOs\Category\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class PublicCategoryDTO implements BaseDTO
{
    public function __construct(
        public int     $categoryId,
        public ?int    $parentId,
        public int     $level,
        public string  $title,
        public string  $slug,
        public ?string $desc = null,
        /** @var PublicCategoryDTO[] */
        public array   $children = [],
    ) {}

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
