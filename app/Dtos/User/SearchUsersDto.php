<?php

namespace App\Dtos\User;

class SearchUsersDto
{
    public function __construct(
        public readonly ?string $query,
        public readonly ?bool $isAdmin,
        public readonly ?int $status,
        public readonly int $page,
        public readonly int $size,
        public readonly string $sortField,
        public readonly string $sortOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            query: $data['query'] ?? null,
            isAdmin: $data['is_admin'] ?? null,
            status: $data['status'] ?? null,
            page: $data['page'] ?? 1,
            size: $data['size'] ?? 10,
            sortField: $data['sort_field'] ?? 'created_at',
            sortOrder: $data['sort_order'] ?? 'desc',
        );
    }

    public function getFilters(): array
    {
        return [
            'query' => $this->query,
            'is_admin' => $this->isAdmin,
            'status' => $this->status,
        ];
    }
}
