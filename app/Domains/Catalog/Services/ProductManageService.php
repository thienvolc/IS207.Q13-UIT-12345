<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Product\Requests\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateCategoriesDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateStatusDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateTagsDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductAdminResponseDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductUpdateStatusResponseDTO;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Infra\Helpers\StringHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class ProductManageService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function create(CreateProductDTO $dto): ProductAdminResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $product = $this->productRepository->create($data);

            return ProductAdminResponseDTO::fromModel($product);
        });
    }

    public function update(UpdateProductDTO $dto): ProductAdminResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($dto->productId);

        return DB::transaction(function () use ($product, $dto) {
            $data = $this->prepareUpdateData($dto);
            $product->update($data);
            $product->refresh();
            $product->load(['categories', 'tags', 'metas']);

            return ProductAdminResponseDTO::fromModel($product);
        });
    }

    public function delete(int $productId): ProductAdminResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($productId);

        return DB::transaction(function () use ($product) {
            if ($product->orderItems()->exists()) {
                $this->archiveProduct($product);
                return $product->fresh();
            }

            $replica = $product->replicate();
            $product->delete();

            return ProductAdminResponseDTO::fromModel($replica);
        });
    }

    public function updateCategories(UpdateCategoriesDTO $dto): ProductAdminResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($dto->productId);

        DB::transaction(function () use ($product, $dto) {
            $product->categories()->sync($dto->categoryIds);
            $this->markAsUpdated($product);
        });

        $product->load(['categories', 'tags', 'metas']);

        return ProductAdminResponseDTO::fromModel($product);
    }

    public function updateTags(UpdateTagsDTO $dto): ProductAdminResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($dto->productId);

        DB::transaction(function () use ($product, $dto) {
            $product->tags()->sync($dto->tagIds);
            $this->markAsUpdated($product);
        });

        $product->load(['categories', 'tags', 'metas']);

        return ProductAdminResponseDTO::fromModel($product);
    }

    public function updateStatus(UpdateStatusDTO $dto): ProductUpdateStatusResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($dto->productId);

        return DB::transaction(function () use ($product, $dto) {
            $updateData = $this->prepareStatusUpdateData($product, $dto->status);
            $product->update($updateData);

            return ProductUpdateStatusResponseDTO::fromModel($product);
        });
    }

    private function prepareCreateData(CreateProductDTO $dto): array
    {
        $userId = $this->userId();
        $data = $dto->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        $data['status'] = $dto->status ?? ProductStatus::ACTIVE;

        return $data;
    }

    private function prepareUpdateData(UpdateProductDTO $dto): array
    {
        $userId = $this->userId();
        $data = $dto->toArray();

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['updated_by'] = $userId;

        return $data;
    }

    private function archiveProduct(Product $product): void
    {
        $product->update([
            'status' => ProductStatus::ARCHIVE,
            'updated_by' => $this->userId(),
        ]);
    }

    private function markAsUpdated(Product $product): void
    {
        $product->update(['updated_by' => $this->userId()]);
    }

    private function prepareStatusUpdateData(Product $product, int $status): array
    {
        $updateData = [
            'status' => $status,
            'updated_by' => $this->userId(),
        ];

        if ($status === ProductStatus::ACTIVE && !$product->published_at) {
            $updateData['published_at'] = now();
        }

        return $updateData;
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
