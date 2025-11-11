<?php

namespace App\Dtos\Product;

readonly class UpdateMetaDto
{
    public function __construct(
        public int $productId,
        public int $metaId,
        public ?string $key,
        public ?string $content
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['productId'],
            metaId: $data['metaId'],
            key: $data['key'] ?? null,
            content: $data['content'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->key !== null) {
            $data['key'] = $this->key;
        }
        if ($this->content !== null) {
            $data['content'] = $this->content;
        }

        return $data;
    }
}
