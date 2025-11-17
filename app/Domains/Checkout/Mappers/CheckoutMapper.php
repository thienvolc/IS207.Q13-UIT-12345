<?php

namespace App\Domains\Checkout\Mappers;

use App\Domains\Cart\Entities\Cart;
use App\Domains\Cart\Mappers\CartMapper;
use App\Domains\Checkout\DTOs\Responses\CartCheckoutDTO;

readonly class CheckoutMapper
{
    public function __construct(
        private CartMapper $cartMapper
    ) {}

    public function toDTO(Cart $cart): CartCheckoutDTO
    {
        $items = $this->cartMapper->toItemDTOs($cart->items);

        return new CartCheckoutDTO(
            cartId: $cart->cart_id,
            itemCount: $items->count(),
            line1: $cart->line1,
            line2: $cart->line2,
            city: $cart->city,
            province: $cart->province,
            country: $cart->country,
            items: $items->toArray()
        );
    }
}
