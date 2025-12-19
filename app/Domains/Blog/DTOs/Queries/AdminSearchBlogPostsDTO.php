<?php

namespace App\Domains\Blog\DTOs\Queries;

readonly class AdminSearchBlogPostsDTO
{
    public function __construct(
        public int $page = 1,
        public int $size = 10,
        public ?string $query = null,
        public ?int $status = null,
        public string $sortField = 'created_at',
        public string $sortOrder = 'desc',
    ) {
    }
}
