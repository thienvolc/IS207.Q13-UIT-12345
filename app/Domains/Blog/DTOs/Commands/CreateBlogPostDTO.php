<?php

namespace App\Domains\Blog\DTOs\Commands;

readonly class CreateBlogPostDTO
{
    public function __construct(
        public string $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $thumb,
        public ?string $summary,
        public ?string $content,
        public ?string $conclusion,
        public int $status,
        public ?string $publishedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'summary' => $this->summary,
            'content' => $this->content,
            'conclusion' => $this->conclusion,
            'status' => $this->status,
            'published_at' => $this->publishedAt,
        ];
    }
}
