<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Domains\Catalog\DTOs\Product\Queries\PublicSearchProductsDTO;
use App\Domains\Catalog\DTOs\Product\Queries\SearchRelatedProductsDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductDTO;
use App\Domains\Catalog\DTOs\Product\Responses\PublicProductDTO;
use App\Domains\Catalog\Mappers\ProductMapper;
use App\Domains\Catalog\Repositories\ProductRepository;
use App\Domains\Common\DTOs\OffsetPageResponseDTO;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;

readonly class ProductReadService
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductMapper     $productMapper
    ) {}

    public function getById(int $productId): ProductDTO
    {
        $product = $this->productRepository->getByIdOrFail($productId);
        return $this->productMapper->toDTO($product);
    }

    public function getPublicById(int $productId): PublicProductDTO
    {
        $product = $this->productRepository->getActiveByIdWithRelationsOrFail($productId);
        return $this->productMapper->toPublicDTO($product);
    }

    public function getPublicBySlug(string $slug): PublicProductDTO
    {
        $product = $this->productRepository->getActiveBySlugWithRelationsOrFail($slug);
        return $this->productMapper->toPublicDTO($product);
    }

    /**
     * @return PageResponseDTO<ProductDTO>
     */
    public function search(AdminSearchProductsDTO $dto): PageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);
        $filters = $dto->getFilter();

        $products = $this->productRepository->search($pageable, $filters);

        return PageResponseDTO::fromPaginator($products,
            fn($p) => $this->productMapper->toDTO($p));
    }

    /**
     * @return OffsetPageResponseDTO<PublicProductDTO>
     */
    public function searchPublic(PublicSearchProductsDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size, $sort);
        $filters = $dto->getFilter();

        $products = $this->productRepository->searchPublic($pageable, $filters);

        return OffsetPageResponseDTO::fromPaginator($products,
            fn($p) => $this->productMapper->toPublicDTO($p));
    }

    /**
     * @return OffsetPageResponseDTO<PublicProductDTO>
     */
    public function searchRelated(SearchRelatedProductsDTO $dto): OffsetPageResponseDTO
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

        return OffsetPageResponseDTO::fromPaginator($products,
            fn($p) => $this->productMapper->toPublicDTO($p));
    }
}
