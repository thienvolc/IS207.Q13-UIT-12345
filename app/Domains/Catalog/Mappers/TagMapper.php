<?php

namespace App\Domains\Catalog\Mappers;

use App\Domains\Catalog\DTOs\Tag\Responses\TagDTO;
use App\Domains\Catalog\Entities\Tag;
use Illuminate\Database\Eloquent\Collection;

readonly class TagMapper
{
    public function toDTO(Tag $tag): TagDTO
    {
        return new TagDTO(
            tagId: $tag->tag_id,
            title: $tag->title,
            metaTitle: $tag->meta_title,
            slug: $tag->slug,
            desc: $tag->desc,
            createdAt: $tag->created_at?->toIso8601String(),
            updatedAt: $tag->updated_at?->toIso8601String(),
            createdBy: $tag->created_by,
            updatedBy: $tag->updated_by,
        );
    }

    /**
     * @param Collection<int, Tag> $tags
     * @return TagDTO[]
     */
    public function toDTOs(Collection $tags): array
    {
        return $tags->map(fn($tag) => $this->toDTO($tag))->all();
    }
}
