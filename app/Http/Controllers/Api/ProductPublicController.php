<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\PublicSearchProductsRequest;
use App\Domains\Catalog\Services\ProductReadService;
use App\Http\Controllers\AppController;

class ProductPublicController extends AppController
{
    public function __construct(
        private readonly ProductReadService $readService
    ) {}

    /**
     * [GET] /api/products
    */
    public function search(PublicSearchProductsRequest $req): ResponseDTO
    {
        $result = $this->readService->searchPublic($req->toDTO());
        return $this->success($result);
    }

    /**
     * [GET] /api/categories/{slug}products
     */
    public function searchByCategorySlug(string $slug, PublicSearchProductsRequest $req): ResponseDTO
    {
        $result = $this->readService->searchPublicByCategorySlug($req->toProductsByCategoryDTO($slug));
        return $this->success($result);
    }

    /**
     * [GET] /api/tags/{tag_id}/products
     */
    public function searchByTagId(int $tag_id, PublicSearchProductsRequest $req): ResponseDTO
    {
        $result = $this->readService->searchPublicByTagId($req->toProductsByTagDTO($tag_id));
        return $this->success($result);
    }

    /**
     * [GET] /api/products/{product_id}
     */
    public function show(int $product_id): ResponseDTO
    {
        $product = $this->readService->getPublicById($product_id);
        return $this->success($product);
    }

    /**
     * [GET] /api/products/{slug}
     */
    public function showBySlug(string $slug): ResponseDTO
    {
        $product = $this->readService->getPublicBySlug($slug);
        return $this->success($product);
    }

    /**
     * [GET] /api/products/{product_id}/related
     */
    public function related(int $product_id, PublicSearchProductsRequest $req): ResponseDTO
    {
        $result = $this->readService->searchRelated($req->toRelatedDTO($product_id));
        return $this->success($result);
    }
}
