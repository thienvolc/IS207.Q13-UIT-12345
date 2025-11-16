<?php

namespace App\Domains\Identity\DTOs\User\Requests;

readonly class SearchUsersDTO
{
    public function __construct(
        public ?string $query,
        public ?bool   $isAdmin,
        public ?int    $status,
        public int     $page,
        public int     $size,
        public string  $sortField,
        public string  $sortOrder,
    )
    {
    }

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
