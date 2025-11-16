<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Product\Responses\ProductPublicDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\CreateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetAllTagsDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetProductsByTagDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\UpdateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Responses\TagDTO;
use App\Domains\Catalog\Entities\Tag;
use App\Domains\Catalog\Repositories\TagRepository;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class TagService
{
    public function __construct(
        private TagRepository $tagRepository
    ) {}

    public function getAllTags(GetAllTagsDTO $dto): array
    {
        $totalCount = $this->tagRepository->count();

        $tags = $this->tagRepository->findAll(
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::offsetLimit(
            TagDTO::collection($tags),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function createTag(CreateTagDTO $dto): array
    {
        $tag = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $tag = $this->tagRepository->create($data);
            return $tag;
        });

        return TagDTO::transform($tag);
    }

    public function updateTag(UpdateTagDTO $dto): array
    {
        $tag = $this->findTagById($dto->tagId);

        $tag = DB::transaction(function () use ($tag, $dto) {
            $data = $this->prepareUpdateData($dto);
            $this->tagRepository->update($tag, $data);
            $tag->refresh();
            return $tag;
        });

        return TagDTO::transform($tag);
    }

    public function deleteTag(int $tagId): array
    {
        $tag = $this->findTagById($tagId);

        $deletedTag = DB::transaction(function () use ($tag) {
            $this->tagRepository->detachAllProducts($tag);
            $deletedTag = $tag->replicate();
            $this->tagRepository->delete($tag);
            return $deletedTag;
        });

        return TagDTO::transform($deletedTag);
    }

    public function getProductsByTagId(GetProductsByTagDTO $dto): array
    {
        $tag = $this->findTagById($dto->tagId);
        $query = $this->buildActiveProductsQuery($tag);
        $totalCount = $query->count();

        $products = $query->orderBy($dto->sortField, $dto->sortOrder)
            ->offset($dto->offset)
            ->limit($dto->limit)
            ->get();

        return PaginationUtil::offsetLimit(
            ProductPublicDTO::collection($products),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    private function findTagById(int $tagId): Tag
    {
        $tag = $this->tagRepository->findById($tagId);

        if (!$tag) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $tag;
    }

    private function prepareCreateData(CreateTagDTO $dto): array
    {
        $userId = $this->getAuthUserId();
        $data = $dto->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        return $data;
    }

    private function prepareUpdateData(UpdateTagDTO $dto): array
    {
        $userId = $this->getAuthUserId();
        $data = $dto->toArray();

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['updated_by'] = $userId;

        return $data;
    }

    private function buildActiveProductsQuery(Tag $tag)
    {
        return $tag->products()
            ->where('status', ProductStatus::ACTIVE)
            ->with(['categories', 'tags', 'metas']);
    }

    private function getAuthUserId(): int
    {
        return Auth::id();
    }
}
