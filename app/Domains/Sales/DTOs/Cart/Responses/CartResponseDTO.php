<?php

namespace App\Domains\Sales\DTOs\Cart\Responses;

use App\Domains\Common\DTOs\BaseDTO;
use App\Domains\Sales\Entities\Cart;

readonly class CartResponseDTO implements BaseDTO
{
    private function __construct(
        public int     $cartId,
        public int     $userId,
        public float   $total,
        public int     $totalQuantity,
        public float   $totalPrice,
        public ?int    $status,
        /** @var CartItemResponseDTO[] */
        public array   $items,
        public ?string $updatedAt,
    ) {}

    public static function fromModel(Cart $cart): self
    {
        $items = $cart->relationLoaded('items') ? $cart->items : collect([]);
        $totalQuantity = $items->sum('quantity');
        $totalPrice = (float)$items->sum(fn ($i) => ($i->price ?? 0) * ($i->quantity ?? 0));

        return new self(
            cartId: $cart->cart_id,
            userId: $cart->user_id,
            total: property_exists($cart, 'total') ? (float)$cart->total : $totalPrice,
            totalQuantity: $totalQuantity,
            totalPrice: $totalPrice,
            status: $cart->status ?? null,
            items: $items->isNotEmpty() ? $items->map(fn($i) => CartItemResponseDTO::fromModel($i))->all() : [],
            updatedAt: optional($cart->updated_at)?->toDateTimeString(),
        );
    }

    public function toArray(): array
    {
        return [
            'cart_id'       => $this->cartId,
            'user_id'       => $this->userId,
            'total'         => $this->total,
            'total_quantity'=> $this->totalQuantity,
            'total_price'   => $this->totalPrice,
            'status'        => $this->status,
            'items'         => array_map(fn($it) => $it->toArray(), $this->items) ?? [],
            'updated_at'    => $this->updatedAt,
        ];
    }
}
