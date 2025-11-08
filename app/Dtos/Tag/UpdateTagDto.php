<?php

namespace App\Dtos\Tag;

readonly class UpdateTagDto
{
    public function __construct(
        public int $tagId,
        public ?string $title,
        public ?string $desc,
        public ?string $slug,
        public ?string $thumbnail
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tagId: $data['tagId'],
            title: $data['title'] ?? null,
            desc: $data['desc'] ?? null,
            slug: $data['slug'] ?? null,
            thumbnail: $data['thumbnail'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        if ($this->desc !== null) {
            $data['desc'] = $this->desc;
        }
        if ($this->slug !== null) {
            $data['slug'] = $this->slug;
        }
        if ($this->thumbnail !== null) {
            $data['thumbnail'] = $this->thumbnail;
        }

        return $data;
    }
}
