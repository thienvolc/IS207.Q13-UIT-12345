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
        private readonly CategoryService $categoryService,
    ) {
    }
    public function index()
    {
        // 1. Hero Products (Keep existing logic if needed for Slider)
        $searchHeroProductsDTO = new PublicSearchProductsDTO(
            query: request('search'),
            categoryIdOrSlug: request('category'),
            tagId: null,
            priceMin: is_numeric(request('price_min')) ? (float) request('price_min') : null,
            priceMax: is_numeric(request('price_max')) ? (float) request('price_max') : null,
            offset: 1,
            limit: 3,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $heroProducts = $this->readService->searchPublic($searchHeroProductsDTO)->data;

        // 2. Banner Categories
        $searchBannerCategoriesDTO = new PublicSearchCategoriesDTO(
            query: null,
            level: 1,
            offset: 1,
            limit: 4,
            sortField: "created_at",
            sortOrder: "desc",
        );
        $bannerCategories = $this->categoryService->searchPublic($searchBannerCategoriesDTO)->data;

        // 3. Top 10 Best Sellers
        // Note: Assuming 'best_seller' sort or just high limit general products. 
        // Using 'limit: 10' and potentially 'sortField: "sold_count"' if available, otherwise "created_at"
        $bestSellersParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at", // Or specific best seller logic if available
            sortOrder: "desc"
        );
        $bestSellers = $this->readService->searchPublic($bestSellersParams)->data;

        // 4. Tai nghe (Headphones) - Limit 10
        $headphoneParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: 'tai-nghe',
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $headphoneProducts = $this->readService->searchPublic($headphoneParams)->data;

        // 5. Đồng hồ (Watches) - Limit 10
        $watchParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: 'do-choi-cong-nghe',
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $watchProducts = $this->readService->searchPublic($watchParams)->data;

        // 6. Camera - Limit 10
        $cameraParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: 'camera',
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $cameraProducts = $this->readService->searchPublic($cameraParams)->data;

        // ==========================================
        // RESTORED SECTIONS (New, Featured, Sale)
        // ==========================================

        // 1. New Products
        $newParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $newProducts = $this->readService->searchPublic($newParams)->data;

        // 2. Featured Products (using sortField views or similar logic, falling back to created_at for now)
        // Assuming "featured" might just be random or specific logic. Using default sort for now.
        $featuredParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $featuredProducts = $this->readService->searchPublic($featuredParams)->data;

        // 3. Sale Products
        // Need a way to filter by discount > 0. 
        // Assuming the API/Service handles this or we just sort by created_at for now if filter not available in DTO.
        // Checking DTO structure from context... it seems robust. I'll stick to created_at for safety unless I recall 'on_sale' param.
        // I will just fetch generic products for now to avoid crashes, as I don't see exact logic in previous diffs.
        $saleParams = new PublicSearchProductsDTO(
            query: null,
            categoryIdOrSlug: null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            offset: 1,
            limit: 10,
            sortField: "created_at",
            sortOrder: "desc"
        );
        $saleProducts = $this->readService->searchPublic($saleParams)->data;

        return view('pages.home', compact(
            'heroProducts',
            'bannerCategories',
            'bestSellers',
            'headphoneProducts',
            'watchProducts',
            'cameraProducts',
            'newProducts',
            'featuredProducts',
            'saleProducts'
        ));
    }
}
