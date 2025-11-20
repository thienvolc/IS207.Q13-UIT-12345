<?php

namespace App\Http\Controllers\Public;

use App\Domains\Catalog\Services\ProductReadService;
use App\Http\Controllers\AppController;

class ProductController extends AppController
{
    public function __construct(
        private ProductReadService $readService
    ) {}

    /**
     * Hiển thị danh sách sản phẩm.
     */
    public function index()
    {
        // Sử dụng service để lấy danh sách sản phẩm
        $limit = (int)request('limit', 20);
        $offset = (int)request('offset', 0);
        $searchQuery = request('search');

        $filters = [
            'category' => request('category'),
            'price_min' => is_numeric(request('price_min')) ? (int)request('price_min') : null,
            'price_max' => is_numeric(request('price_max')) ? (int)request('price_max') : null,
        ];

        // Thêm search query vào filters nếu có (key phải là 'query' theo repository)
        if ($searchQuery) {
            $filters['query'] = $searchQuery;
        }

        $sortField = match(request('sort')) {
            'price_asc' => 'price',
            'price_desc' => 'price',
            'name' => 'name',
            default => 'created_at',
        };
        $sortOrder = match(request('sort')) {
            'price_asc' => 'asc',
            'price_desc' => 'desc',
            default => 'desc',
        };

        $products = $this->readService->getAllWithOffset($limit, $offset, $filters, $sortField, $sortOrder);
        $hasMore = count($products) === $limit;

        return view('pages.products.index', [
            'products' => $products,
            'limit' => $limit,
            'offset' => $offset,
            'hasMore' => $hasMore,
            'searchQuery' => $searchQuery
        ]);
    }
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

    public function showBySlugView(string $slug)
    {
        /** @var ProductPublicResource $product */
        $product = $this->readService->getBySlugForPublic($slug);

        $productId = $product['product_id'];
        [$sortField, $sortOrder] = ['created_at', 'desc'];

        //        $dto = GetRelatedProductsDto::fromArray([
        //            'productId' => $productId,
        //            'offset' => 0,
        //            'limit' => 4,
        //            'sortField' => $sortField,
        //            'sortOrder' => $sortOrder,
        //        ]);
        //
        //        $related = $this->readService->getRelatedProductsById($dto);
        $related = null;

        return view('pages.products.detail', compact('product', 'related'));
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
