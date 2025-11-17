<?php

namespace App\Domains\Catalog\DTOs\Product\Responses;

use App\Domains\Common\DTOs\BaseDTO;

class ProductMetaDTO implements BaseDTO
{
    public function __construct(
        public int    $metaId,
        public string $key,
        public string $content,
    ) {}

    public function toArray(): array
    {
        return [
            'meta_id' => $this->metaId,
            'key' => $this->key,
            'content' => $this->content,
        ];
    }
}
