<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

class ProductMetaResponseDTO implements BaseDTO
{
    public function __construct(
        public int    $metaId,
        public string $key,
        public string $content,
    ) {}

    static public function fromModel(object $meta): self
    {
        return new self(
            metaId: $meta->meta_id,
            key: $meta->key,
            content: $meta->content,
        );
    }

    public function toArray(): array
    {
        return [
            'meta_id' => $this->metaId,
            'key' => $this->key,
            'content' => $this->content,
        ];
    }
}
