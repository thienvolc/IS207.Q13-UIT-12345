<?php

namespace App\Domains\Catalog\DTOs\Tag\Requests;

readonly class UpdateTagDTO
{
    public function __construct(
        public int     $tagId,
        public ?string $title,
        public ?string $metaTitle,
        public ?string $slug,
        public ?string $desc
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tagId: $data['tag_id'],
            title: $data['title'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            slug: $data['slug'] ?? null,
            desc: $data['desc'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->title !== null) $data['title'] = $this->title;
        if ($this->metaTitle !== null) $data['meta_title'] = $this->metaTitle;
        if ($this->slug !== null) $data['slug'] = $this->slug;
        if ($this->desc !== null) $data['desc'] = $this->desc;
        return $data;
    }
}
