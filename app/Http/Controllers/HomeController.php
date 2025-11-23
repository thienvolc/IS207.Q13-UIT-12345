<?php

namespace App\Http\Controllers;

use App\Domains\Catalog\DTOs\Category\Queries\PublicSearchCategoriesDTO;
use App\Domains\Catalog\DTOs\Product\Queries\PublicSearchProductsDTO;
use App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO;
use App\Domains\Catalog\Services\CategoryService;
use App\Domains\Catalog\Services\ProductReadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductReadService $readService,
        private readonly CategoryService    $categoryService,
    ) {}
    public function index()
    {
        $searchHeroProductsDTO = new PublicSearchProductsDTO(
            query: request('search'),
            categoryIdOrSlug: request('category'),
            tagId: null,
            priceMin: is_numeric(request('price_min')) ? (float)request('price_min') : null,
            priceMax: is_numeric(request('price_max')) ? (float)request('price_max') : null,
            offset: 1,
            limit: 3,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $heroProducts = $this->readService->searchPublic($searchHeroProductsDTO)->data;

        $searchBannerCategoriesDTO = new PublicSearchCategoriesDTO(
            query: null,
            level: 1,
            offset: 1,
            limit: 4,
            sortField: "created_at",
            sortOrder: "desc",
        );
        $bannerCategories = $this->categoryService->searchPublic($searchBannerCategoriesDTO)->data;

        $searchProductsDTO = new PublicSearchProductsDTO(
            query: request('search'),
            categoryIdOrSlug: request('category'),
            tagId: null,
            priceMin: is_numeric(request('price_min')) ? (float)request('price_min') : null,
            priceMax: is_numeric(request('price_max')) ? (float)request('price_max') : null,
            offset: 1,
            limit: 40,
            sortField: "created_at",
            sortOrder: "desc"
        );
        /** @var array<PublicProductDTO> $products*/
        $products = $this->readService->searchPublic($searchProductsDTO)->data;

        $newProducts = array_slice($products, 0, 8);
        $featuredProducts = array_slice($products, 8, 16);
        $saleProducts = array_slice($products, 16, 24);
        $bestSellers = array_slice($products, 24, 40);

        return view('pages.home', compact(
            'heroProducts',
            'bannerCategories',
            'newProducts',
            'featuredProducts',
            'saleProducts',
            'bestSellers'));
    }
}
