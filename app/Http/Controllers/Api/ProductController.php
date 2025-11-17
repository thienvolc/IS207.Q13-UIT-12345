<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\AdjustInventoryRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\AdminSearchProductRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\CreateProductMetaRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\CreateProductRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductCategoriesRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductMetaRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductStatusRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductTagsRequest;
use App\Domains\Catalog\DTOs\Product\Requests\AdjustInventoryDTO;
use App\Domains\Catalog\DTOs\Product\Requests\CreateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Requests\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsAdminDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateCategoriesDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateMetaDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateProductDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateStatusDTO;
use App\Domains\Catalog\DTOs\Product\Requests\UpdateTagsDTO;
use App\Domains\Catalog\Services\ProductManageService;
use App\Domains\Catalog\Services\ProductMetaService;
use App\Domains\Catalog\Services\ProductReadService;
use App\Domains\Catalog\Services\ProductStockService;
use App\Http\Controllers\AppController;

class ProductController extends AppController
{
    public function __construct(
        private readonly ProductReadService   $readService,
        private readonly ProductManageService $manageService,
        private readonly ProductStockService  $productStockService,
        private readonly ProductMetaService   $productMetaService
    )
    {
    }

    public function index(AdminSearchProductRequest $request): ResponseDTO
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchProductsAdminDTO::fromArray([
            ...$filters,
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->search($dto);

        return $this->success($result);
    }

    public function store(CreateProductRequest $request): ResponseDTO
    {
        $dto = CreateProductDTO::fromArray($request->validated());

        $product = $this->manageService->create($dto);

        return $this->created($product);
    }

    public function show(int $product_id): ResponseDTO
    {
        $product = $this->readService->getById($product_id);

        return $this->success($product);
    }

    public function update(UpdateProductRequest $request, int $product_id): ResponseDTO
    {
        $dto = UpdateProductDTO::fromArray([
            'productId' => $product_id,
            ...$request->validated()
        ]);

        $product = $this->manageService->update($dto);

        return $this->success($product);
    }

    public function destroy(int $product_id): ResponseDTO
    {
        $product = $this->manageService->delete($product_id);

        return $this->success($product);
    }

    public function updateCategories(UpdateProductCategoriesRequest $request, int $product_id): ResponseDTO
    {
        $dto = UpdateCategoriesDTO::fromArray([
            'productId' => $product_id,
            'categoryIds' => $request->input('category_ids')
        ]);

        $product = $this->manageService->updateCategories($dto);

        return $this->success($product);
    }

    public function updateTags(UpdateProductTagsRequest $request, int $product_id): ResponseDTO
    {
        $dto = UpdateTagsDTO::fromArray([
            'productId' => $product_id,
            'tagIds' => $request->input('tag_ids')
        ]);

        $product = $this->manageService->updateTags($dto);

        return $this->success($product);
    }

    public function updateStatus(UpdateProductStatusRequest $request, int $product_id): ResponseDTO
    {
        $dto = UpdateStatusDTO::fromArray([
            'productId' => $product_id,
            'status' => $request->input('status')
        ]);

        $product = $this->manageService->updateStatus($dto);

        return $this->success($product);
    }

    public function adjustInventory(AdjustInventoryRequest $request, int $product_id): ResponseDTO
    {
        $dto = AdjustInventoryDTO::fromArray([
            'productId' => $product_id,
            'amount' => $request->input('amount'),
            'operationType' => $request->input('operationType'),
            'reason' => $request->input('reason')
        ]);

        $product = $this->productStockService->adjustInventory($dto);

        return $this->success($product);
    }

    public function storeMeta(CreateProductMetaRequest $request, int $product_id): ResponseDTO
    {
        $dto = CreateMetaDTO::fromArray([
            'productId' => $product_id,
            'key' => $request->input('key'),
            'content' => $request->input('content')
        ]);

        $meta = $this->productMetaService->create($dto);

        return $this->success($meta);
    }

    public function updateMeta(UpdateProductMetaRequest $request, int $product_id, int $meta_id): ResponseDTO
    {
        $dto = UpdateMetaDTO::fromArray([
            'productId' => $product_id,
            'metaId' => $meta_id,
            ...$request->validated()
        ]);

        $meta = $this->productMetaService->update($dto);

        return $this->success($meta);
    }

    public function destroyMeta(int $product_id, int $meta_id): ResponseDTO
    {
        $meta = $this->productMetaService->delete($product_id, $meta_id);

        return $this->success($meta);
    }
}
