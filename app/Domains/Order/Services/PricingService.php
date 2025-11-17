<?php

namespace App\Domains\Order\Services;

use App\Domains\Cart\Entities\Cart;
use App\Domains\Order\Constants\PricingConstant;
use App\Domains\Order\DTOs\OrderPrice;

readonly class PricingService
{
    public function calculate(Cart $cart, ?string $promo): OrderPrice
    {
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
        $discountTotal = $cart->items->sum(fn($i) => $i->discount * $i->quantity);

        $netAmount = $subtotal - $discountTotal;
        $tax = $netAmount * PricingConstant::DEFAULT_TAX_RATE;
        $shipping = PricingConstant::DEFAULT_SHIPPING_FEE;

        $promoDiscount = !empty($promo)
            ? $netAmount * PricingConstant::DEFAULT_PROMO_DISCOUNT_RATE
            : 0;

        $grandTotal = $netAmount - $promoDiscount + $tax + $shipping;
        $grandTotal = ($grandTotal < 0) ? 0 : $grandTotal;

        return new OrderPrice(
            subtotal: $subtotal,
            discountTotal: $discountTotal,
            tax: $tax,
            shipping: $shipping,
            promoDiscount: $promoDiscount,
            grandTotal: $grandTotal,
        );
    }
}
