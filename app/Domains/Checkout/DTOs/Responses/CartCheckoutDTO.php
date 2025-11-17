<?php

namespace App\Domains\Checkout\DTOs\Responses;

use App\Domains\Cart\DTOs\Responses\CartItemDTO;
use App\Domains\Common\DTOs\BaseDTO;

readonly class CartCheckoutDTO implements BaseDTO
{
    public function __construct(
        public int    $cartId,
        public int    $itemCount,
        public string $line1,
        public string $line2,
        public string $city,
        public string $province,
        public string $country,
        /** @var CartItemDTO[] */
        public array  $items
    ) {}

    public function toArray(): array
    {
        return [
            'cart_id' => $this->cartId,
            'item_count' => $this->itemCount,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'province' => $this->province,
            'country' => $this->country,
            'items' => array_map(fn($it) => $it->toArray(), $this->items),
        ];
    }
}
