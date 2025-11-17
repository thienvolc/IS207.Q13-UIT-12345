<?php

namespace App\Domains\Catalog\DTOs\Product\Commands;

readonly class UpdateProductMetaDTO
{
    public function __construct(
        public int     $productId,
        public int     $metaId,
        public ?string $key,
        public ?string $content
    )
    {
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
