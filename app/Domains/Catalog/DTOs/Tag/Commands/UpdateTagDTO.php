<?php

namespace App\Domains\Catalog\DTOs\Tag\Commands;

readonly class UpdateTagDTO
{
    public function __construct(
        public int     $tagId,
        public ?string $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc
    )
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc,
        ], fn($v) => !is_null($v));
    }
}
