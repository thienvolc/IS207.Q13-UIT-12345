<?php

namespace App\Domains\Catalog\DTOs\Category\Commands;

readonly class UpdateCategoryDTO
{
    public function __construct(
        public int     $categoryId,
        public ?int    $parentId,
        public ?int    $level,
        public ?string $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc
    ) {}

    public function toArray(): array
    {
        return [
            'parent_id' => $this->parentId,
            'level' => $this->level,
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc
        ];
    }
}
