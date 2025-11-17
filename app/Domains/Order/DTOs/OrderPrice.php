<?php

namespace App\Domains\Order\DTOs;

class OrderPrice
{
    public function __construct(
        public float $subtotal,
        public float $discountTotal,
        public float $tax,
        public float $shipping,
        public float $promoDiscount,
        public float $grandTotal
    ) {}
}
