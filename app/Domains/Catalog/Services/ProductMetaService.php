<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Product\Commands\CreateProductMetaDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductMetaDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductMetaDTO;
use App\Domains\Catalog\Mappers\ProductMapper;
use App\Domains\Catalog\Repositories\ProductMetaRepository;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;

readonly class ProductMetaService
{
    public function __construct(
        private ProductRepository     $productRepository,
        private ProductMetaRepository $productMetaRepository,
        private ProductMapper         $productMapper,
    ) {}

    public function create(CreateProductMetaDTO $dto): ProductMetaDTO
    {
        $this->assertProductExists($dto->productId);
        $meta = $this->productMetaRepository->create($dto->toArray());

        return $this->productMapper->toMetaDTO($meta);
    }

    public function update(UpdateProductMetaDTO $dto): ProductMetaDTO
    {
        $this->assertProductExists($dto->productId);
        $meta = $this->productMetaRepository->getByIdAndProductOrFail($dto->metaId, $dto->productId);

        $meta->update($dto->toArray());
        return $this->productMapper->toMetaDTO($meta);
    }

    public function delete(int $productId, int $metaId): ProductMetaDTO
    {
        $meta = $this->productMetaRepository->getByIdAndProductOrFail($metaId, $productId);

        $replica = $meta->replicate();
        $meta->delete();

        return $this->productMapper->toMetaDTO($replica);
    }

    private function assertProductExists(int $productId): void
    {
        $exists = $this->productRepository->existsById($productId);

        if (!$exists) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }
    }
}
