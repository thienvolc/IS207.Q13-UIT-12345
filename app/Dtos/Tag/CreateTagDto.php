<?php

namespace App\Dtos\Tag;

readonly class CreateTagDto
{
    public function __construct(
        public string $title,
        public ?string $desc,
        public ?string $slug,
        public ?string $thumbnail
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            desc: $data['desc'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'desc' => $this->desc,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
