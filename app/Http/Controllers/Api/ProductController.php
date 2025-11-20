<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Product\Commands\AssignProductCategoriesDTO;
use App\Domains\Catalog\DTOs\Product\Commands\AssignProductTagsDTO;
use App\Domains\Catalog\DTOs\Product\Commands\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Commands\CreateProductMetaDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductMetaDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductStatusDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\AdminSearchProductsRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\AssignProductCategoriesRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\AssignProductTagsRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\CreateProductMetaRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\CreateProductRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductsRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductMetaRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductRequest;
use App\Domains\Catalog\DTOs\Product\FormRequests\UpdateProductStatusRequest;
use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Domains\Catalog\Services\ProductManageService;
use App\Domains\Catalog\Services\ProductMetaService;
use App\Domains\Catalog\Services\ProductReadService;
use App\Domains\Inventory\DTOs\Commands\AdjustInventoryDTO;
use App\Domains\Inventory\DTOs\FormRequests\AdjustInventoryRequest;
use App\Domains\Inventory\Services\ProductStockService;
use App\Http\Controllers\AppController;

class ProductController extends AppController
{
    public function __construct(
        private readonly ProductReadService   $readService,
        private readonly ProductManageService $manageService,
        private readonly ProductStockService  $productStockService,
        private readonly ProductMetaService   $productMetaService
    ) {}

    /**
     * [GET] /api/admin/products
    */
    public function index(AdminSearchProductsRequest $req): ResponseDTO
    {
        $result = $this->readService->search($req->toDTO());
        return $this->success($result);
    }

    /**
     * [POST] /api/admin/products
     */
    public function store(CreateProductRequest $req): ResponseDTO
    {
        $product = $this->manageService->create($req->toDTO());
        return $this->created($product);
    }

    /**
     * [GET] /api/admin/products/{product_id}
     */
    public function show(int $product_id): ResponseDTO
    {
        $product = $this->readService->getById($product_id);
        return $this->success($product);
    }

    /**
     * [PUT] /api/admin/products/{product_id}
     */
    public function update(UpdateProductRequest $req, int $product_id): ResponseDTO
    {
        $product = $this->manageService->update($req->toDTO($product_id));
        return $this->success($product);
    }

    /**
     * [DELETE] /api/admin/products/{product_id}
     */
    public function destroy(int $product_id): ResponseDTO
    {
        $product = $this->manageService->delete($product_id);
        return $this->success($product);
    }

    /**
     * [PUT] /api/admin/products/{product_id}/categories
     */
    public function updateCategories(AssignProductCategoriesRequest $req, int $product_id): ResponseDTO
    {
        $product = $this->manageService->updateCategories($req->toDTO($product_id));
        return $this->success($product);
    }

    /**
     * [PATCH] /api/admin/products/{product_id}/tags
     */
    public function updateTags(AssignProductTagsRequest $req, int $product_id): ResponseDTO
    {
        $product = $this->manageService->updateTags($req->toDTO($product_id));
        return $this->success($product);
    }

    /**
     * [PATCH] /api/admin/products/{product_id}/status
     */
    public function updateStatus(UpdateProductStatusRequest $req, int $product_id): ResponseDTO
    {
        $product = $this->manageService->updateStatus($req->toDTO($product_id));
        return $this->success($product);
    }

    /**
     * [PATCH] /api/admin/products/{product_id}/inventories
     */
    public function adjustInventory(AdjustInventoryRequest $req, int $product_id): ResponseDTO
    {
        $product = $this->productStockService->adjustInventory($req->toDTO($product_id));
        return $this->success($product);
    }

    /**
     * [POST] /api/admin/products/{product_id}/metas
     */
    public function storeMeta(CreateProductMetaRequest $req, int $product_id): ResponseDTO
    {
        $meta = $this->productMetaService->create($req->toDTO($product_id));
        return $this->success($meta);
    }

    /**
     * [PUT] /api/admin/products/{product_id}/metas/{meta_id}
     */
    public function updateMeta(UpdateProductMetaRequest $req, int $product_id, int $meta_id): ResponseDTO
    {
        $meta = $this->productMetaService->update($req->toDTO($product_id, $meta_id));
        return $this->success($meta);
    }

    /**
     * [DELETE] /api/admin/products/{product_id}/metas/{meta_id}
     */
    public function destroyMeta(int $product_id, int $meta_id): ResponseDTO
    {
        $meta = $this->productMetaService->delete($product_id, $meta_id);
        return $this->success($meta);
    }
}
