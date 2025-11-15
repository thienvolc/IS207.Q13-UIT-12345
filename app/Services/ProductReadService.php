<?php

namespace App\Services;

use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Dtos\Product\GetRelatedProductsDto;
use App\Dtos\Product\SearchProductsAdminDto;
use App\Dtos\Product\SearchProductsPublicDto;
use App\Exceptions\BusinessException;
use App\Http\Resources\ProductAdminResource;
use App\Http\Resources\ProductPublicResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Utils\PaginationUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductReadService
{
    /**
     * Lấy sản phẩm public với phân trang (limit, offset).
     */
    public function getAllWithOffset(
        int $limit = 12, 
        int $offset = 0, 
        array $filters = [], 
        string $sortField = 'created_at', 
        string $sortOrder = 'desc'
    ): array
    {
        $dto = \App\Dtos\Product\ListProductsPublicDto::fromArray([
            'limit' => $limit,
            'offset' => $offset,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'filters' => $filters
        ]);
        $products = $this->productRepository->searchActiveWithFilters(
            $dto->getFilters(),
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );
        return ProductPublicResource::collection($products);
    }
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Lấy tất cả sản phẩm public (giới hạn 100).
     */
    public function getAll(int $limit = 100): array
    {
        $dto = \App\Dtos\Product\ListProductsPublicDto::fromArray([
            'limit' => $limit,
            'offset' => 0,
            'sortField' => 'created_at',
            'sortOrder' => 'desc',
            'filters' => []
        ]);
        $products = $this->productRepository->searchActiveWithFilters(
            $dto->getFilters(),
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );
        return ProductPublicResource::collection($products);
    }
    public function getByIdForAdmin(int $productId): array
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductAdminResource::transform($product);
    }

    public function getByIdForPublic(int $productId): array
    {
        $product = $this->productRepository->findActiveById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicResource::transform($product);
    }

    public function getBySlugForPublic(string $slug): array
    {
        $product = $this->productRepository->findActiveBySlug($slug);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicResource::transform($product);
    }

    public function searchForAdmin(SearchProductsAdminDto $dto): array
    {
        $totalCount = $this->productRepository->countWithFilters($dto->getFilters());

        $products = $this->productRepository->searchWithFilters(
            $dto->getFilters(),
            $dto->sortField,
            $dto->sortOrder,
            ($dto->page - 1) * $dto->size,
            $dto->size
        );

        return PaginationUtil::fromPageSize(
            ProductAdminResource::collection($products),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function searchForPublic(SearchProductsPublicDto $dto): array
    {
        $totalCount = $this->productRepository->countActiveWithFilters($dto->getFilters());

        $products = $this->productRepository->searchActiveWithFilters(
            $dto->getFilters(),
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::fromOffsetLimit(
            ProductPublicResource::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function getRelatedProductsById(GetRelatedProductsDto $dto): array
    {
        $product = $this->productRepository->findActiveWithRelations($dto->productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        $categoryIds = $this->extractCategoryIds($product);
        $tagIds = $this->extractTagIds($product);

        $totalCount = $this->productRepository->countRelatedProducts(
            $dto->productId,
            $categoryIds,
            $tagIds
        );

        $products = $this->productRepository->searchRelatedProducts(
            $dto->productId,
            $categoryIds,
            $tagIds,
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::fromOffsetLimit(
            ProductPublicResource::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    private function extractCategoryIds(Product $product): array
    {
        //        return $product->categories()->pluck('pivot.category_id')->toArray();
        return DB::table('product_categories')
            ->where('product_id', $product->product_id)
            ->pluck('category_id')
            ->toArray();
    }

    private function extractTagIds(Product $product): array
    {
        //        return $product->tags()->pluck('pivot.tag_id')->toArray();
        return DB::table('product_tags')
            ->where('product_id', $product->product_id)
            ->pluck('tag_id')
            ->toArray();
    }
}
