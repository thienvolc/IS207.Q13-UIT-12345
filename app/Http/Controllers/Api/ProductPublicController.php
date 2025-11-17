<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Category\Requests\GetProductsByCategoryDTO;
use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductRequest;
use App\Domains\Catalog\DTOs\Product\Requests\GetRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsPublicDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetProductsByTagDTO;
use App\Domains\Catalog\Services\ProductReadService;
use App\Http\Controllers\AppController;

class ProductPublicController extends AppController
{
    public function __construct(
        private readonly ProductReadService $readService
    ) {}

    public function search(SearchProductRequest $request): ResponseDTO
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchProductsPublicDTO::fromArray([
            ...$filters,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchPublic($dto);

        return $this->success($result);
    }

    public function searchByCategorySlug(string $slug, SearchProductRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetProductsByCategoryDTO::fromArray([
            'slug' => $slug,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchPublicByCategorySlug($dto);

        return $this->success($result);
    }

    public function searchByTagId(int $tag_id, SearchProductRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetProductsByTagDto::fromArray([
            'tagId' => $tag_id,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchPublicByTagId($dto);

        return $this->success($result);
    }

    public function show(int $product_id): ResponseDTO
    {
        $product = $this->readService->getPublicById($product_id);

        return $this->success($product);
    }

    public function showBySlug(string $slug): ResponseDTO
    {
        $product = $this->readService->getPublicBySlug($slug);

        return $this->success($product);
    }

    public function related(int $product_id, SearchProductRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetRelatedProductsDTO::fromArray([
            'productId' => $product_id,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchRelated($dto);

        return $this->success($result);
    }
}
