<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Product\Requests\AdjustInventoryDTO;
use App\Domains\Catalog\DTOs\Product\Requests\CreateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Requests\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateCategoriesDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateStatusDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateTagsDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductAdminDTO;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Entities\ProductMeta;
use App\Domains\Catalog\Repositories\ProductMetaRepository;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Helpers\StringHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class ProductManageService
{
    public function __construct(
        private ProductRepository     $productRepository,
        private ProductMetaRepository $productMetaRepository
    ) {}

    public function createProduct(CreateProductDTO $dto): array
    {
        $product = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $product = $this->productRepository->create($data);
            $product->load(['categories', 'tags', 'metas']);
            return $product;
        });

        return ProductAdminDTO::transform($product);
    }

    public function updateProduct(UpdateProductDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $product = DB::transaction(function () use ($product, $dto) {
            $data = $this->prepareUpdateData($dto);
            $product->update($data);
            $product->refresh();
            $product->load(['categories', 'tags', 'metas']);
            return $product;
        });

        return ProductAdminDTO::transform($product);
    }

    public function deleteProduct(int $productId): array
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $deletedProduct = DB::transaction(function () use ($product) {
            if ($product->orderItems()->exists()) {
                $this->archiveProduct($product);
                return $product->fresh();
            }

            $deletedProduct = $product->replicate();
            $product->delete();
            return $deletedProduct;
        });

        return ProductAdminDTO::transform($deletedProduct);
    }

    public function updateCategories(UpdateCategoriesDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        DB::transaction(function () use ($product, $dto) {
            $product->categories()->sync($dto->categoryIds);
            $this->markAsUpdated($product);
        });

        $product->load(['categories', 'tags', 'metas']);
        return ProductAdminDTO::transform($product);
    }

    public function updateTags(UpdateTagsDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        DB::transaction(function () use ($product, $dto) {
            $product->tags()->sync($dto->tagIds);
            $this->markAsUpdated($product);
        });

        $product->load(['categories', 'tags', 'metas']);
        return ProductAdminDTO::transform($product);
    }

    public function updateStatus(UpdateStatusDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        DB::transaction(function () use ($product, $dto) {
            $updateData = $this->prepareStatusUpdateData($product, $dto->status);
            $product->update($updateData);
        });

        $product = $product->fresh()->load(['categories', 'tags', 'metas']);
        return ProductAdminDTO::transform($product);
    }

    public function adjustInventory(AdjustInventoryDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $product = DB::transaction(function () use ($product, $dto) {
            $product->refresh();
            $newQuantity = $this->calculateNewQuantity($product, $dto);

            $product->update([
                'quantity' => $newQuantity,
                'updated_by' => $this->getAuthUserId(),
            ]);

            return $product->fresh();
        });

        return ProductAdminDTO::transform($product);
    }

    public function createMeta(CreateMetaDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $meta = $this->productMetaRepository->create([
            'product_id' => $product->product_id,
            'key' => $dto->key,
            'content' => $dto->content,
        ]);

        return $this->formatMetaResponse($meta);
    }

    public function updateMeta(UpdateMetaDTO $dto): array
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $meta = $this->productMetaRepository->findByIdAndProductId($dto->metaId, $dto->productId);

        if (!$meta) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $this->productMetaRepository->update($meta, $dto->toArray());
        $this->markAsUpdated($product);

        return $this->formatMetaResponse($meta);
    }

    public function deleteMeta(int $productId, int $metaId): array
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $meta = $this->productMetaRepository->findByIdAndProductId($metaId, $productId);

        if (!$meta) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $metaData = $this->formatMetaResponse($meta);

        $this->productMetaRepository->delete($meta);
        $this->markAsUpdated($product);

        return $metaData;
    }

    private function prepareCreateData(CreateProductDTO $dto): array
    {
        $userId = $this->getAuthUserId();
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
        $userId = $this->getAuthUserId();
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
            'updated_by' => $this->getAuthUserId(),
        ]);
    }

    private function markAsUpdated(Product $product): void
    {
        $product->update(['updated_by' => $this->getAuthUserId()]);
    }

    private function prepareStatusUpdateData(Product $product, int $status): array
    {
        $updateData = [
            'status' => $status,
            'updated_by' => $this->getAuthUserId(),
        ];

        if ($status === ProductStatus::ACTIVE && !$product->published_at) {
            $updateData['published_at'] = now();
        }

        return $updateData;
    }

    private function calculateNewQuantity(Product $product, AdjustInventoryDTO $dto): int
    {
        $currentQuantity = $product->quantity;

        if ($dto->operationType === 'increase') {
            return $currentQuantity + $dto->amount;
        }

        $newQuantity = $currentQuantity - $dto->amount;

        if ($newQuantity < 0) {
            throw new BusinessException(
                ResponseCode::BAD_REQUEST,
                ['available' => $currentQuantity, 'requested' => $dto->amount]
            );
        }

        return $newQuantity;
    }

    private function formatMetaResponse(ProductMeta $meta): array
    {
        return [
            'meta_id' => $meta->meta_id,
            'key' => $meta->key,
            'content' => $meta->content,
        ];
    }

    private function getAuthUserId(): int
    {
        return Auth::id();
    }
}
