<?php

namespace App\Domains\Identity\DTOs\User\Queries;

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

    public function getFilters(): UserFilter
    {
        return new UserFilter(
            query: $this->query,
            isAdmin: $this->isAdmin,
            status: $this->status,
        );
    }
}
