<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\Product\AdjustInventoryDto;
use App\Dtos\Product\CreateMetaDto;
use App\Dtos\Product\CreateProductDto;
use App\Dtos\Product\SearchProductsAdminDto;
use App\Dtos\Product\UpdateCategoriesDto;
use App\Dtos\Product\UpdateMetaDto;
use App\Dtos\Product\UpdateProductDto;
use App\Dtos\Product\UpdateStatusDto;
use App\Dtos\Product\UpdateTagsDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Product\AdminSearchProductRequest;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\UpdateProductStatusRequest;
use App\Http\Requests\Product\UpdateProductTagsRequest;
use App\Http\Requests\Product\UpdateProductCategoriesRequest;
use App\Http\Requests\Product\CreateProductMetaRequest;
use App\Http\Requests\Product\UpdateProductMetaRequest;
use App\Http\Requests\Product\AdjustInventoryRequest;
use App\Services\ProductReadService;
use App\Services\ProductManageService;
use Illuminate\Http\JsonResponse;

class ProductController extends AppController
{
    public function __construct(
        private ProductReadService $readService,
        private ProductManageService $manageService
    ) {}

    public function index(AdminSearchProductRequest $request): JsonResponse
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchProductsAdminDto::fromArray([
            ...$filters,
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchForAdmin($dto);

        return $this->success($result);
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $dto = CreateProductDto::fromArray($request->validated());

        $product = $this->manageService->createProduct($dto);

        return $this->created($product);
    }

    public function show(int $product_id): JsonResponse
    {
        $product = $this->readService->getByIdForAdmin($product_id);

        return $this->success($product);
    }

    public function update(UpdateProductRequest $request, int $product_id): JsonResponse
    {
        $dto = UpdateProductDto::fromArray([
            'productId' => $product_id,
            ...$request->validated()
        ]);

        $product = $this->manageService->updateProduct($dto);

        return $this->success($product);
    }

    public function destroy(int $product_id): JsonResponse
    {
        $product = $this->manageService->deleteProduct($product_id);

        return $this->success($product);
    }

    public function updateCategories(UpdateProductCategoriesRequest $request, int $product_id): JsonResponse
    {
        $dto = UpdateCategoriesDto::fromArray([
            'productId' => $product_id,
            'categoryIds' => $request->input('category_ids')
        ]);

        $product = $this->manageService->updateCategories($dto);

        return $this->success($product);
    }

    public function updateTags(UpdateProductTagsRequest $request, int $product_id): JsonResponse
    {
        $dto = UpdateTagsDto::fromArray([
            'productId' => $product_id,
            'tagIds' => $request->input('tag_ids')
        ]);

        $product = $this->manageService->updateTags($dto);

        return $this->success($product);
    }

    public function updateStatus(UpdateProductStatusRequest $request, int $product_id): JsonResponse
    {
        $dto = UpdateStatusDto::fromArray([
            'productId' => $product_id,
            'status' => $request->input('status')
        ]);

        $product = $this->manageService->updateStatus($dto);

        return $this->success($product);
    }

    public function adjustInventory(AdjustInventoryRequest $request, int $product_id): JsonResponse
    {
        $dto = AdjustInventoryDto::fromArray([
            'productId' => $product_id,
            'amount' => $request->input('amount'),
            'operationType' => $request->input('operationType'),
            'reason' => $request->input('reason')
        ]);

        $product = $this->manageService->adjustInventory($dto);

        return $this->success($product);
    }

    public function storeMeta(CreateProductMetaRequest $request, int $product_id): JsonResponse
    {
        $dto = CreateMetaDto::fromArray([
            'productId' => $product_id,
            'key' => $request->input('key'),
            'content' => $request->input('content')
        ]);

        $meta = $this->manageService->createMeta($dto);

        return $this->success($meta);
    }

    public function updateMeta(UpdateProductMetaRequest $request, int $product_id, int $meta_id): JsonResponse
    {
        $dto = UpdateMetaDto::fromArray([
            'productId' => $product_id,
            'metaId' => $meta_id,
            ...$request->validated()
        ]);

        $meta = $this->manageService->updateMeta($dto);

        return $this->success($meta);
    }

    public function destroyMeta(int $product_id, int $meta_id): JsonResponse
    {
        $meta = $this->manageService->deleteMeta($product_id, $meta_id);

        return $this->success($meta);
    }
}
