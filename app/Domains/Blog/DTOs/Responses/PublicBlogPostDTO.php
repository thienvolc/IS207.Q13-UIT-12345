<?php

namespace App\Domains\Blog\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class PublicBlogPostDTO implements BaseDTO
{
    public function __construct(
        public int $blogpostId,
        public string $title,
        public string $slug,
        public ?string $thumb,
        public ?string $summary,
        public ?string $content,
        public ?string $conclusion,
        public ?string $publishedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'blogpost_id' => $this->blogpostId,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumb' => $this->thumb,
            'summary' => $this->summary,
            'content' => $this->content,
            'conclusion' => $this->conclusion,
            'published_at' => $this->publishedAt,
        ];
    }
}
