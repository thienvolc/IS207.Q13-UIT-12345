<?php

namespace App\Domains\Catalog\DTOs\Tag\Requests;

readonly class GetProductsByTagDTO
{
    public function __construct(
        public int    $tagId,
        public int    $offset,
        public int    $limit,
        public string $sortField,
        public string $sortOrder
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tagId: $data['tagId'],
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
