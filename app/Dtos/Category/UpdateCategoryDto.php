<?php

namespace App\Dtos\Category;

readonly class UpdateCategoryDto
{
    public function __construct(
        public int $categoryId,
        public ?string $title,
        public ?string $desc,
        public ?string $slug,
        public ?string $thumbnail,
        public ?int $level,
        public ?int $parentId,
        public ?array $children
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            categoryId: $data['categoryId'],
            title: $data['title'] ?? null,
            desc: $data['desc'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            level: $data['level'] ?? null,
            parentId: $data['parent_id'] ?? null,
            children: $data['children'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        if ($this->desc !== null) {
            $data['desc'] = $this->desc;
        }
        if ($this->slug !== null) {
            $data['slug'] = $this->slug;
        }
        if ($this->thumbnail !== null) {
            $data['thumbnail'] = $this->thumbnail;
        }
        if ($this->level !== null) {
            $data['level'] = $this->level;
        }
        if ($this->parentId !== null) {
            $data['parent_id'] = $this->parentId;
        }
        if ($this->children !== null) {
            $data['children'] = $this->children;
        }

        return $data;
    }
}
