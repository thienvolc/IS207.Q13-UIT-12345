<?php

namespace App\Domains\Catalog\DTOs\Tag\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class TagDTO implements BaseDTO
{
    public function __construct(
        public int     $tagId,
        public string  $title,
        public ?string $metaTitle,
        public string  $slug,
        public ?string $desc = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?int    $createdBy = null,
        public ?int    $updatedBy = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'tag_id' => $this->tagId,
            'title' => $this->title,
            'meta_title' => $this->metaTitle,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}

