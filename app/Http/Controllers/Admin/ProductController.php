<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * GET /admin/products
     */
    public function index(AdminSearchProductRequest $request): JsonResponse
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->readService->searchForAdmin(
            $filters,
            $request->getPage(),
            $request->getSize(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }

    /**
     * POST /admin/products
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        $product = $this->manageService->createProduct($request->validated());

        return $this->createdResponse($product);
    }

    /**
     * GET /admin/products/{id}
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->readService->getByIdForAdmin($id);

        return $this->success($product);
    }

    /**
     * PUT /admin/products/{id}
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->manageService->updateProduct($id, $request->validated());

        return $this->success($product);
    }

    /**
     * DELETE /admin/products/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $product = $this->manageService->deleteProduct($id);

        return $this->success($product);
    }

    /**
     * PUT /admin/products/{id}/categories
     */
    public function updateCategories(UpdateProductCategoriesRequest $request, int $id): JsonResponse
    {
        $product = $this->manageService->updateCategories($id, $request->input('category_ids'));

        return $this->success($product);
    }

    /**
     * PATCH /admin/products/{id}/tags
     */
    public function updateTags(UpdateProductTagsRequest $request, int $id): JsonResponse
    {
        $product = $this->manageService->updateTags($id, $request->input('tag_ids'));

        return $this->success($product);
    }

    /**
     * PATCH /admin/products/{id}/status
     */
    public function updateStatus(UpdateProductStatusRequest $request, int $id): JsonResponse
    {
        $product = $this->manageService->updateStatus($id, $request->input('status'));

        return $this->success($product);
    }

    /**
     * PATCH /admin/products/{id}/inventory/adjust
     */
    public function adjustInventory(AdjustInventoryRequest $request, int $id): JsonResponse
    {
        $product = $this->manageService->adjustInventory(
            $id,
            $request->input('amount'),
            $request->input('operationType'),
            $request->input('reason')
        );

        return $this->success($product);
    }

    /**
     * POST /admin/products/{id}/meta
     */
    public function storeMeta(CreateProductMetaRequest $request, int $id): JsonResponse
    {
        $meta = $this->manageService->createMeta(
            $id,
            $request->input('key'),
            $request->input('content')
        );

        return $this->success($meta);
    }

    /**
     * PUT /admin/products/{id}/meta/{metaId}
     */
    public function updateMeta(UpdateProductMetaRequest $request, int $id, int $metaId): JsonResponse
    {
        $meta = $this->manageService->updateMeta($id, $metaId, $request->validated());

        return $this->success($meta);
    }

    /**
     * DELETE /admin/products/{id}/meta/{metaId}
     */
    public function destroyMeta(int $id, int $metaId): JsonResponse
    {
        $meta = $this->manageService->deleteMeta($id, $metaId);

        return $this->success($meta);
    }
}
