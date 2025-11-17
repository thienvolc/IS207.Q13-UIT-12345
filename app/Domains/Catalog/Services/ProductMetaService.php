<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Product\Requests\CreateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductMetaResponseDTO;
use App\Domains\Catalog\Repositories\ProductMetaRepository;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;

readonly class ProductMetaService
{
    public function __construct(
        private ProductRepository     $productRepository,
        private ProductMetaRepository $productMetaRepository
    ) {}

    public function create(CreateMetaDTO $dto): ProductMetaResponseDTO
    {
        $this->assertProductExists($dto->productId);
        $meta = $this->productMetaRepository->create($dto->toArray());
        return ProductMetaResponseDTO::fromModel($meta);
    }

    public function update(UpdateMetaDTO $dto): ProductMetaResponseDTO
    {
        $this->assertProductExists($dto->productId);
        $meta = $this->productMetaRepository->getByIdAndProductOrFail($dto->metaId, $dto->productId);

        $meta->update($dto->toArray());
        return ProductMetaResponseDTO::fromModel($meta);
    }

    public function delete(int $productId, int $metaId): ProductMetaResponseDTO
    {
        $meta = $this->productMetaRepository->getByIdAndProductOrFail($metaId, $productId);

        $replica = $meta->replicate();
        $meta->delete();

        return ProductMetaResponseDTO::fromModel($replica);
    }

    private function assertProductExists(int $productId): void
    {
        $exists = $this->productRepository->existsById($productId);

        if (!$exists) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }
    }
}
