<?php

namespace App\Domains\Sales\DTOs\Order;

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
