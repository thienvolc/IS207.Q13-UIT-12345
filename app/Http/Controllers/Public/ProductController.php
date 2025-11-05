<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\AppController;
use App\Http\Requests\Product\SearchProductRequest;
use App\Services\ProductReadService;
use Illuminate\Http\JsonResponse;

class ProductController extends AppController
{
    public function __construct(
        private ProductReadService $readService
    ) {}

    /**
     * GET /products
     */
    public function search(SearchProductRequest $request): JsonResponse
    {
        $filters = $request->only(['query', 'category', 'price_min', 'price_max']);
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->readService->searchForPublic(
            $filters,
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }

    /**
     * GET /products/{id}
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->readService->getByIdForPublic($id);

        return $this->success($product);
    }

    /**
     * GET /products/{slug}
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $product = $this->readService->getBySlugForPublic($slug);

        return $this->success($product);
    }

    /**
     * GET /products/{id}/related
     */
    public function related(int $id, SearchProductRequest $request): JsonResponse
    {
        $result = $this->readService->getRelatedProductsById(
            $id,
            $request->getOffset(),
            $request->getLimit(),
            $request->getSort()[0],
            $request->getSort()[1]
        );

        return $this->success($result);
    }
}
