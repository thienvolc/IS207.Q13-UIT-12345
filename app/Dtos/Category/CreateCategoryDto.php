<?php

namespace App\Dtos\Category;

readonly class CreateCategoryDto
{
    public function __construct(
        public string $title,
        public ?string $desc,
        public ?string $slug,
        public ?string $thumbnail,
        public int $level,
        public ?int $parentId,
        public ?array $children
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            desc: $data['desc'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            level: $data['level'],
            parentId: $data['parent_id'] ?? null,
            children: $data['children'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'desc' => $this->desc,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'level' => $this->level,
            'parent_id' => $this->parentId,
            'children' => $this->children,
        ];
    }
}
