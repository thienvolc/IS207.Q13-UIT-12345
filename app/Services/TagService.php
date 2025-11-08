<?php

namespace App\Services;

use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Dtos\Tag\CreateTagDto;
use App\Dtos\Tag\GetAllTagsDto;
use App\Dtos\Tag\GetProductsByTagDto;
use App\Dtos\Tag\UpdateTagDto;
use App\Exceptions\BusinessException;
use App\Helpers\StringHelper;
use App\Http\Resources\ProductPublicResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Utils\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagService
{
    public function __construct(
        private TagRepository $tagRepository
    ) {
    }

    public function getAllTags(GetAllTagsDto $dto): array
    {
        $totalCount = $this->tagRepository->count();

        $tags = $this->tagRepository->findAll(
            $dto->sortField,
            $dto->sortOrder,
            $dto->offset,
            $dto->limit
        );

        return PaginationUtil::fromOffsetLimit(
            TagResource::collection($tags),
            $dto->limit,
            $dto->offset,
            $totalCount
        );
    }

    public function createTag(CreateTagDto $dto): array
    {
        $tag = DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $tag = $this->tagRepository->create($data);
            return $tag;
        });

        return TagResource::transform($tag);
    }

    public function updateTag(UpdateTagDto $dto): array
    {
        $tag = $this->findTagById($dto->tagId);

        $tag = DB::transaction(function () use ($tag, $dto) {
            $data = $this->prepareUpdateData($dto);
            $this->tagRepository->update($tag, $data);
            $tag->refresh();
            return $tag;
        });

        return TagResource::transform($tag);
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

        return TagResource::transform($deletedTag);
    }

    public function getProductsByTagId(GetProductsByTagDto $dto): array
    {
        $tag = $this->findTagById($dto->tagId);
        $query = $this->buildActiveProductsQuery($tag);
        $totalCount = $query->count();

        $products = $query->orderBy($dto->sortField, $dto->sortOrder)
            ->offset($dto->offset)
            ->limit($dto->limit)
            ->get();

        return PaginationUtil::fromOffsetLimit(
            ProductPublicResource::collection($products),
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

    private function prepareCreateData(CreateTagDto $dto): array
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

    private function prepareUpdateData(UpdateTagDto $dto): array
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
