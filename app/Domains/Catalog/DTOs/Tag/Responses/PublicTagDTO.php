<?php

namespace App\Domains\Catalog\DTOs\Tag\Responses;

readonly class PublicTagDTO
{
    public function __construct(
        public int     $tagId,
        public string  $title,
        public ?string $metaTitle,
        public string  $slug,
        public ?string $desc = null,
    ) {
    }
}
