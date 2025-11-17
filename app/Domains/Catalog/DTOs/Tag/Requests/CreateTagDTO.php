<?php

namespace App\Domains\Catalog\DTOs\Tag\Requests;

readonly class CreateTagDTO
{
    public function __construct(
        public string  $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            metaTitle: $data['meta_title'] ?? null,
            slug: $data['slug'] ?? null,
            desc: $data['desc'] ?? null
        );
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc,
        ];
    }
}
