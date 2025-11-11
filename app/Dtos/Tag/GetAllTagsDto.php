<?php

namespace App\Dtos\Tag;

readonly class GetAllTagsDto
{
    public function __construct(
        public int $offset,
        public int $limit,
        public string $sortField,
        public string $sortOrder
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 10,
            sortField: $data['sortField'] ?? 'created_at',
            sortOrder: $data['sortOrder'] ?? 'desc'
        );
    }
}
