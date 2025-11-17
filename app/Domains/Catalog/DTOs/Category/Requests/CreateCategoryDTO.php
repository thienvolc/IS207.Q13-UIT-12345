<?php

namespace App\Domains\Catalog\DTOs\Category\Requests;

readonly class CreateCategoryDTO
{
    public function __construct(
        public ?int    $parentId,
        public int     $level,
        public string  $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            parentId: $data['parent_id'] ?? null,
            level: $data['level'] ?? 0,
            title: $data['title'],
            metaTitle: $data['meta_title'] ?? null,
            slug: $data['slug'] ?? null,
            desc: $data['desc'] ?? null
        );
    }

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
