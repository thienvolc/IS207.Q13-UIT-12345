<?php

namespace App\Domains\Catalog\DTOs\Tag\Requests;

readonly class GetAllTagsDTO
{
    public function __construct(
        public int $offset,
        public int $limit
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 50
        );
    }
}
