<?php

namespace App\Domains\Cart\DTOs\Responses;

use App\Domains\Cart\Entities\Cart;
use App\Domains\Common\DTOs\BaseDTO;

readonly class CartDTO implements BaseDTO
{
    public function __construct(
        public int     $cartId,
        public int     $userId,
        public int     $totalQuantity,
        public float   $totalPrice,
        public ?int    $status,
        /** @var CartItemDTO[] */
        public array   $items,
        public ?string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'cart_id'       => $this->cartId,
            'user_id'       => $this->userId,
            'total_quantity'=> $this->totalQuantity,
            'total_price'   => $this->totalPrice,
            'status'        => $this->status,
            'items'         => array_map(fn($it) => $it->toArray(), $this->items) ?? [],
            'updated_at'    => $this->updatedAt,
        ];
    }
}
