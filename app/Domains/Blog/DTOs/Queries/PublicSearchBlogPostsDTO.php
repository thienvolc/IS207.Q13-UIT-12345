<?php

namespace App\Domains\Blog\DTOs\Queries;

readonly class PublicSearchBlogPostsDTO
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
        public ?string $query = null,
        public string $sortField = 'published_at',
        public string $sortOrder = 'desc',
    ) {
    }
}
