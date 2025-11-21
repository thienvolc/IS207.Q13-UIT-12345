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
        private ProductReadService $readService
    ) {}

    /**
     * Hiển thị danh sách sản phẩm.
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
            'products' => array_map(fn($p) => $p->toArray(), $result->data),
            'limit' => $limit,
            'offset' => $offset,
            'hasMore' => $result->hasMore,
            'searchQuery' => request('search')
        ]);
    }
    // Nếu cần search qua API thì dùng hàm index hoặc tạo hàm mới dùng PublicSearchProductsDTO và searchPublic

    public function show(int $product_id)
    {
        $productDTO = $this->readService->getPublicById($product_id);
        $product = $productDTO->toArray();
        return view('pages.products.detail', compact('product'));
    }

    public function showBySlugView(string $slug)
    {
        $productDTO = $this->readService->getPublicBySlug($slug);
        $product = $productDTO->toArray();
        // Lấy sản phẩm liên quan nếu cần
        $related = null;
        return view('pages.products.detail', compact('product', 'related'));
    }

    // Nếu cần trả về JSON thì tạo hàm mới dùng getPublicBySlug

    public function related(int $product_id, Request $request)
    {
        $offset = (int)$request->input('offset', 0);
        $limit = (int)$request->input('limit', 4);
        $sortField = $request->input('sortField', 'created_at');
        $sortOrder = $request->input('sortOrder', 'desc');

        $dto = new SearchRelatedProductsDTO(
            productId: $product_id,
            offset: $offset,
            limit: $limit,
            sortField: $sortField,
            sortOrder: $sortOrder
        );
        $result = $this->readService->searchRelated($dto);
        $related = array_map(fn($p) => $p->toArray(), $result->data);
        return view('pages.products.related', compact('related'));
    }
}
