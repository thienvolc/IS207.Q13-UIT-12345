<?php

namespace App\Domains\Catalog\DTOs\Tag\Commands;

readonly class CreateTagDTO
{
    public function __construct(
        public string  $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc,
        ];
    }
}
