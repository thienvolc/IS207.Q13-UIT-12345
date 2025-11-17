<?php

namespace App\Domains\Catalog\DTOs\Tag\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class TagResponseDTO implements BaseDTO
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

    public static function fromModel($tag): self
    {
        return new self(
            tagId: $tag->tag_id,
            title: $tag->title,
            metaTitle: $tag->meta_title,
            slug: $tag->slug,
            desc: $tag->desc,
            createdAt: optional($tag->created_at)?->toIso8601String(),
            updatedAt: optional($tag->updated_at)?->toIso8601String(),
            createdBy: $tag->created_by,
            updatedBy: $tag->updated_by,
        );
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

    public static function collection($tags): array
    {
        return $tags->map(fn($tag) => self::fromModel($tag)->toArray())->toArray();
    }
}

