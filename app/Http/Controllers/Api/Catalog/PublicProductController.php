<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductRequest;
use App\Domains\Catalog\DTOs\Product\Requests\GetRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsPublicDTO;
use App\Domains\Catalog\Services\ProductReadService;
use App\Http\Controllers\AppController;
use App\Applications\DTOs\Responses\ResponseDTO;

class PublicProductController extends AppController
{
    public function __construct(
        private readonly ProductReadService $readService
    )
    {
    }

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

        $result = $this->readService->searchForPublic($dto);

        return $this->success($result);
    }

    public function show(int $product_id): ResponseDTO
    {
        $product = $this->readService->getByIdForPublic($product_id);

        return $this->success($product);
    }

    public function showBySlug(string $slug): ResponseDTO
    {
        $product = $this->readService->getBySlugForPublic($slug);

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

        $result = $this->readService->getRelatedProductsById($dto);

        return $this->success($result);
    }
}
