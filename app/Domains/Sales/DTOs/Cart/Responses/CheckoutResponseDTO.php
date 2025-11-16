<?php

namespace App\Domains\Sales\DTOs\Cart\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Cart;

readonly class CheckoutResponseDTO implements BaseDTO
{
    public function __construct(
        public int    $cartId,
        public int    $itemCount,
        public string $line1,
        public string $line2,
        public string $city,
        public string $province,
        public string $country,
        /** @var CartItemResponseDTO[] */
        public array  $items
    ) {}

    public static function fromModel(Cart $cart): self
    {
        $items = $cart->items
            ->map(fn($i) => CartItemResponseDTO::fromModel($i))->all()
            ?? collect();

        return new self(
            cartId: $cart->cart_id,
            itemCount: $items->count(),
            line1: $cart->line1,
            line2: $cart->line2,
            city: $cart->city,
            province: $cart->province,
            country: $cart->country,
            items: $items
        );
    }

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
