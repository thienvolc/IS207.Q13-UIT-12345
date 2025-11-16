<?php

namespace App\Domains\Sales\Services;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

readonly class ProductAvailabilityService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function lockAndValidate(Collection $cartItems): void
    {
        $productIds = $cartItems->pluck('product_id')->unique()->sort()->values()->all();
        $products = $this->productRepository->findLockedByIds($productIds);

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->relationLoaded('product') || !$cartItem->product) {
                throw new BusinessException(ResponseCode::PRODUCT_NOT_AVAILABLE,
                    ['available' => 0, 'requested' => $cartItem->quantity]);
            }
            $product = $products->get($cartItem->product_id);
            $this->assertProductActive($product);
            $this->assertStockAvailable($product, $cartItem->quantity);
        }
    }

    public function assertStockAvailable(Product $product, int $requestedQuantity): void
    {
        if ($product->quantity < $requestedQuantity) {
            throw new BusinessException(
                ResponseCode::PRODUCT_NOT_AVAILABLE,
                ['available' => $product->quantity, 'requested' => $requestedQuantity]
            );
        }
    }

    public function assertSufficientStock(Product $product, int $totalQuantity, int $inCartQuantity): void
    {
        if ($product->quantity < $totalQuantity) {
            throw new BusinessException(ResponseCode::BAD_REQUEST, [], [
                'message' => 'Not enough product in stock',
                'available' => $product->quantity,
                'in_cart' => $inCartQuantity,
                'requested' => $totalQuantity - $inCartQuantity,
                'total_needed' => $totalQuantity,
            ]);
        }
    }

    private function assertProductActive(Product $product): void
    {
        if ($product->status !== ProductStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::PRODUCT_NOT_ACTIVE, ['title' => $product->title]);
        }
    }
}
