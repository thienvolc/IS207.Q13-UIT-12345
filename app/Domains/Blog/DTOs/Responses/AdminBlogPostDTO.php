<?php

namespace App\Domains\Blog\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class AdminBlogPostDTO implements BaseDTO
{
    public function __construct(
        public int $blogpostId,
        public string $title,
        public ?string $metaTitle,
        public string $slug,
        public ?string $thumb,
        public ?string $summary,
        public ?string $content,
        public ?string $conclusion,
        public int $status,
        public ?string $publishedAt,
        public ?int $createdBy,
        public ?int $updatedBy,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'blogpost_id' => $this->blogpostId,
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'summary' => $this->summary,
            'content' => $this->content,
            'conclusion' => $this->conclusion,
            'status' => $this->status,
            'published_at' => $this->publishedAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
