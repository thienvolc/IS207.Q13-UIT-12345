<?php

namespace App\Http\Resources;

class TagResource
{
    public static function transform($tag): array
    {
        return [
            'tag_id' => $tag->tag_id,
            'title' => $tag->title,
            'meta_title' => $tag->meta_title,
            'slug' => $tag->slug,
            'desc' => $tag->desc,
            'created_at' => $tag->created_at?->toIso8601String(),
            'updated_at' => $tag->updated_at?->toIso8601String(),
            'created_by' => $tag->created_by,
            'updated_by' => $tag->updated_by,
        ];
    }

    public static function collection($tags): array
    {
        return $tags->map(fn($tag) => self::transform($tag))->toArray();
    }
}

