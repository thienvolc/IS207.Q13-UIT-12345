<?php

namespace App\Domains\Inventory\DTOs\Commands;

readonly class AdjustInventoryDTO
{
    public function __construct(
        public int     $productId,
        public int     $amount,
        public string  $operationType,
        public ?string $reason
    )
    {
    }
}
