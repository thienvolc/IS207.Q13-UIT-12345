<?php

namespace App\Domains\Catalog\Services;

use App\Applications\DTOs\Responses\OffsetPageResponseDTO;
use App\Applications\DTOs\Responses\PageResponseDTO;
use App\Domains\Catalog\DTOs\Category\Requests\GetProductsByCategoryDTO;
use App\Domains\Catalog\DTOs\Product\Requests\GetRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsAdminDTO;
use App\Domains\Catalog\DTOs\Product\Requests\SearchProductsPublicDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductAdminResponseDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductPublicResponseDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetProductsByTagDTO;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;

readonly class ProductReadService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function getById(int $productId): ProductAdminResponseDTO
    {
        $product = $this->productRepository->getByIdOrFail($productId);
        return ProductAdminResponseDTO::fromModel($product);
    }

    public function getPublicById(int $productId): ProductPublicResponseDTO
    {
        $product = $this->productRepository->getActiveByIdWithRelationsOrFail($productId);
        return ProductPublicResponseDTO::fromModel($product);
    }

    public function getPublicBySlug(string $slug): ProductPublicResponseDTO
    {
        $product = $this->productRepository->getActiveBySlugWithRelationsOrFail($slug);
        return ProductPublicResponseDTO::fromModel($product);
    }

    /** @return OffsetPageResponseDTO<ProductPublicResponseDTO> */
    public function searchPublicByCategorySlug(GetProductsByCategoryDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $products = $this->productRepository->searchPublicByCategorySlug($pageable, $dto->slug);

        return OffsetPageResponseDTO::fromPaginator($products);
    }

    /** @return OffsetPageResponseDTO<ProductPublicResponseDTO> */
    public function searchPublicByTagId(GetProductsByTagDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $products = $this->productRepository->searchPublicByTagId($pageable, $dto->tagId);

        return OffsetPageResponseDTO::fromPaginator($products);
    }

    /** @return PageResponseDTO<ProductAdminResponseDTO> */
    public function search(SearchProductsAdminDTO $dto): PageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);
        $filters = $dto->getFilters();

        $products = $this->productRepository->search($pageable, $filters);

        return PageResponseDTO::fromPaginator($products);
    }

    /** @return OffsetPageResponseDTO<ProductPublicResponseDTO> */
    public function searchPublic(SearchProductsPublicDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);
        $filters = $dto->getFilters();

        $products = $this->productRepository->searchPublic($pageable, $filters);

        return OffsetPageResponseDTO::fromPaginator($products);
    }

    /** @return OffsetPageResponseDTO<ProductPublicResponseDTO> */
    public function searchRelated(GetRelatedProductsDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);

        $product = $this->productRepository->getActiveByIdWithRelationsOrFail($dto->productId);
        $categoryIds = $product->categories->pluck('category_id')->toArray();
        $tagIds = $product->tags->pluck('tag_id')->toArray();

        $products = $this->productRepository->searchRelated(
            $pageable,
            $dto->productId,
            $categoryIds,
            $tagIds
        );

        return OffsetPageResponseDTO::fromPaginator($products);
    }
}
