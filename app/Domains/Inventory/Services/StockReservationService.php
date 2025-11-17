<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Cart\Entities\CartItem;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Order\Entities\Order;
use App\Exceptions\BusinessException;

readonly class StockReservationService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function reserveProductStock(CartItem $cartItem): void
    {
        $requestedQuantity = $cartItem->quantity;
        $product = $cartItem->product;

        $updated = $this->productRepository->decrementStock($product->product_id, $requestedQuantity);

        if (!$updated) {
            throw new BusinessException(ResponseCode::PRODUCT_NOT_AVAILABLE,
                ['available' => $product->quantity ?? 0, 'requested' => $requestedQuantity]);
        }
    }

    public function restoreAllProductStockInOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }
    }

}
