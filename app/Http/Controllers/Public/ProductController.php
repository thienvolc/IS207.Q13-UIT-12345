<?php

namespace App\Http\Controllers\Public;

use App\Domains\Catalog\Services\ProductReadService;
use App\Domains\Catalog\DTOs\Product\Queries\SearchRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Queries\PublicSearchProductsDTO;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductReadService $readService
    ) {}

    /**
     * [GET] /san-pham [products.index]
     */
    public function index()
    {
        $limit = (int)request('limit', 20);
        $offset = (int)request('offset', 0);

        [$sortField, $sortOrder] = match (request('sort')) {
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name' => ['name', 'asc'],
            default => ['created_at', 'desc'],
        };

        $dto = new PublicSearchProductsDTO(
            query: request('search'),
            categoryIdOrSlug: request('category'),
            tagId: null,
            priceMin: is_numeric(request('price_min')) ? (float)request('price_min') : null,
            priceMax: is_numeric(request('price_max')) ? (float)request('price_max') : null,
            offset: $offset,
            limit: $limit,
            sortField: $sortField,
            sortOrder: $sortOrder
        );

        $result = $this->readService->searchPublic($dto);

        return view('pages.products.index', [
            'searchQuery' => request('search'),
            'searchProductsResponse' => $result,
        ]);
    }

    public function showBySlugView(string $slug)
    {
        $product = $this->readService->getPublicBySlug($slug);

        $requestDTO = new SearchRelatedProductsDTO(
            productId: $product->productId,
            offset: 1,
            limit: 5,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        $relatedProductsResponse = $this->readService->searchRelated($requestDTO);

        return view('pages.products.detail', [
            'product' => $product,
            'related' => $relatedProductsResponse,
        ]);
    }

    public function show(int $product_id)
    {
        $product = $this->readService->getPublicById($product_id);

        $requestDTO = new SearchRelatedProductsDTO(
            productId: $product_id,
            offset: 1,
            limit: 4,
            sortField: 'created_at',
            sortOrder: 'desc',
        );

        $relatedProductsResponse = $this->readService->searchRelated($requestDTO);

        return view('pages.products.detail', [
            'product' => $product,
            'related' => $relatedProductsResponse,
        ]);
    }
}
