<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Product\Requests\GetRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsAdminDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsPublicDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductAdminDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductPublicDTO;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\PaginationUtil;

readonly class ProductReadService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function getByIdForAdmin(int $productId): array
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductAdminDTO::transform($product);
    }

    public function getByIdForPublic(int $productId): array
    {
        $product = $this->productRepository->findActiveById($productId);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicDTO::transform($product);
    }

    public function getBySlugForPublic(string $slug): array
    {
        $product = $this->productRepository->findActiveBySlug($slug);

        if (!$product) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return ProductPublicDTO::transform($product);
    }

    public function searchForAdmin(SearchProductsAdminDTO $dto): array
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
            ProductAdminDTO::collection($products),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function searchForPublic(SearchProductsPublicDTO $dto): array
    {
        $totalCount = $this->productRepository->countActiveWithFilters($dto->getFilters());

        $products = $this->productRepository->searchActiveWithFilters(
            $dto->getFilters(),
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::offsetLimit(
            ProductPublicDTO::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function getRelatedProductsById(GetRelatedProductsDTO $dto): array
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

        return PaginationUtil::offsetLimit(
            ProductPublicDTO::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    private function extractCategoryIds(Product $product): array
    {
        return $product->categories()->pluck('category_id')->toArray();
    }

    private function extractTagIds(Product $product): array
    {
        return $product->tags()->pluck('tag_id')->toArray();
    }
}
