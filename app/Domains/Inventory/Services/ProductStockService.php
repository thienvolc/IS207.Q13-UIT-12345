<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Catalog\DTOs\Product\Responses\ProductDTO;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Mappers\ProductMapper;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Inventory\Constants\StockOperationType;
use App\Domains\Inventory\DTOs\Commands\AdjustInventoryDTO;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class ProductStockService
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductMapper     $productMapper,
    ) {}

    public function adjustInventory(AdjustInventoryDTO $dto): ProductDTO
    {
        $product = $this->productRepository->getByIdOrFail($dto->productId);

        return DB::transaction(function () use ($product, $dto) {
            $product->refresh();

            $dto->operationType === StockOperationType::INCREASE
                ? $this->increment($product, $dto->amount)
                : $this->decrement($product, $dto->amount);

            $this->markAsUpdated($product);

            return $this->productMapper->toDTO($product->fresh());
        });
    }

    private function decrement(Product $product, int $quantity): void
    {
        $updated = $this->productRepository->decrementStock($product->product_id, $quantity);

        if (!$updated) {
            throw new BusinessException(ResponseCode::PRODUCT_NOT_AVAILABLE,
                ['available' => $product->quantity ?? 0, 'requested' => $quantity]);
        }
    }

    private function increment(Product $product, int $quantity): void
    {
        $product->increment('quantity', $quantity);
    }

    private function markAsUpdated(Product $product): void
    {
        $product->update(['updated_by' => $this->userId()]);
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
