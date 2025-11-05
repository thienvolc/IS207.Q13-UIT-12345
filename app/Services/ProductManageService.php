<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductMeta;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Helpers\StringHelper;
use App\Http\Resources\ProductAdminResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductManageService
{
    public function createProduct(array $data): array
    {
        $product = DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $userId = Auth::id();
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;
            $data['status'] = $data['status'] ?? ProductStatus::ACTIVE;

            $product = Product::create($data);
            $product->load(['categories', 'tags', 'metas']);

            return $product;
        });

        return ProductAdminResource::transform($product);
    }

    public function updateProduct(int $productId, array $data): array
    {
        $product = $this->getProductModel($productId);

        $product = DB::transaction(function () use ($product, $data) {
            if (isset($data['title']) && empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $data['updated_by'] = Auth::id();

            $product->update($data);
            $product->refresh();
            $product->load(['categories', 'tags', 'metas']);

            return $product;
        });

        return ProductAdminResource::transform($product);
    }

    public function deleteProduct(int $productId): array
    {
        $product = $this->getProductModel($productId);

        $deletedProduct = DB::transaction(function () use ($product) {
            if ($product->orderItems()->exists()) {
                $product->update([
                    'status' => ProductStatus::ARCHIVE,
                    'updated_by' => Auth::id(),
                ]);
                return $product->fresh();
            }

            $deletedProduct = $product->replicate();
            $product->delete();
            return $deletedProduct;
        });

        return ProductAdminResource::transform($deletedProduct);
    }

    public function updateCategories(int $productId, array $categoryIds): array
    {
        $product = $this->getProductModel($productId);

        DB::transaction(function () use ($product, $categoryIds) {
            $product->categories()->sync($categoryIds);
            $product->update(['updated_by' => Auth::id()]);
        });

        $product->load(['categories', 'tags', 'metas']);
        return ProductAdminResource::transform($product);
    }

    public function updateTags(int $productId, array $tagIds): array
    {
        $product = $this->getProductModel($productId);

        DB::transaction(function () use ($product, $tagIds) {
            $product->tags()->sync($tagIds);
            $product->update(['updated_by' => Auth::id()]);
        });

        $product->load(['categories', 'tags', 'metas']);
        return ProductAdminResource::transform($product);
    }

    public function updateStatus(int $productId, int $status): array
    {
        $product = $this->getProductModel($productId);

        DB::transaction(function () use ($product, $status) {
            $updateData = [
                'status' => $status,
                'updated_by' => Auth::id(),
            ];

            if ($status === ProductStatus::ACTIVE && !$product->published_at) {
                $updateData['published_at'] = now();
            }

            $product->update($updateData);
        });

        $product = $product->fresh()->load(['categories', 'tags', 'metas']);
        return ProductAdminResource::transform($product);
    }

    public function adjustInventory(int $productId, int $amount, string $operationType, ?string $reason = null): array
    {
        $product = $this->getProductModel($productId);

        $product = DB::transaction(function () use ($product, $amount, $operationType, $reason) {
            $product->refresh();
            $currentQuantity = $product->quantity;

            if ($operationType === 'increase') {
                $newQuantity = $currentQuantity + $amount;
            } else {
                $newQuantity = $currentQuantity - $amount;

                if ($newQuantity < 0) {
                    throw new BusinessException(
                        ResponseCode::BAD_REQUEST,
                        ['available' => $currentQuantity, 'requested' => $amount]
                    );
                }
            }

            $product->update([
                'quantity' => $newQuantity,
                'updated_by' => Auth::id(),
            ]);

            return $product->fresh();
        });

        return ProductAdminResource::transform($product);
    }

    public function createMeta(int $productId, string $key, string $content): array
    {
        $product = $this->getProductModel($productId);

        $meta = ProductMeta::create([
            'product_id' => $product->product_id,
            'key' => $key,
            'content' => $content,
        ]);

        return [
            'meta_id' => $meta->meta_id,
            'key' => $meta->key,
            'content' => $meta->content,
        ];
    }

    public function updateMeta(int $productId, int $metaId, array $data): array
    {
        $product = $this->getProductModel($productId);

        $meta = ProductMeta::where('meta_id', $metaId)
            ->where('product_id', $productId)
            ->first();

        if (!$meta) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $meta->update($data);
        $product->update(['updated_by' => Auth::id()]);

        return [
            'meta_id' => $meta->meta_id,
            'key' => $meta->key,
            'content' => $meta->content,
        ];
    }

    public function deleteMeta(int $productId, int $metaId): array
    {
        $product = $this->getProductModel($productId);

        $meta = ProductMeta::where('meta_id', $metaId)
            ->where('product_id', $productId)
            ->first();

        if (!$meta) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $metaData = [
            'meta_id' => $meta->meta_id,
            'key' => $meta->key,
            'content' => $meta->content,
        ];

        $meta->delete();
        $product->update(['updated_by' => Auth::id()]);

        return $metaData;
    }

    private function getProductModel(int $productId): Product
    {
        $product = Product::with(['categories', 'tags', 'metas'])->find($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $product;
    }
}

