<?php

namespace App\Domains\Order\DTOs\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class OrderDTO implements BaseDTO
{
    public function __construct(
        public int $orderId,
        public int $userId,
        public float $total,
        public ?int $status = null,
        public ?float $subtotal = null,
        public ?float $tax = null,
        public ?float $shipping = null,
        public ?float $discountTotal = null,
        public ?float $discount = null,
        public ?float $grandTotal = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $city = null,
        public ?string $province = null,
        /** @var OrderItemDTO[] */
        public array $items = [],
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'user_id' => $this->userId,
            'total' => $this->total,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'discount_total' => $this->discountTotal,
            'discount' => $this->discount,
            'grand_total' => $this->grandTotal,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'province' => $this->province,
            'items' => array_map(fn($it) => $it->toArray(), $this->items),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

