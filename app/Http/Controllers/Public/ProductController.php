<?php

namespace App\Http\Controllers\Public;

use App\Dtos\Product\GetRelatedProductsDto;
use App\Dtos\Product\SearchProductsPublicDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Product\SearchProductRequest;
use App\Services\ProductReadService;
use Illuminate\Http\JsonResponse;

class ProductController extends AppController
{
    public function __construct(
        private ProductReadService $readService
    ) {}

    public function search(SearchProductRequest $request): JsonResponse
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchProductsPublicDto::fromArray([
            ...$filters,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->readService->searchForPublic($dto);

        return $this->success($result);
    }

    public function show(int $product_id): JsonResponse
    {
        $product = $this->readService->getByIdForPublic($product_id);

        return $this->success($product);
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $product = $this->readService->getBySlugForPublic($slug);

        return $this->success($product);
    }

    public function related(int $product_id, SearchProductRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetRelatedProductsDto::fromArray([
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
