<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\DTOs\Tag\Commands\CreateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Commands\UpdateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Queries\SearchTagsDTO;
use App\Domains\Catalog\DTOs\Tag\Responses\TagDTO;
use App\Domains\Catalog\Mappers\TagMapper;
use App\Domains\Catalog\Repositories\TagRepository;
use App\Domains\Common\DTOs\OffsetPageResponseDTO;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class TagService
{
    public function __construct(
        private TagRepository $tagRepository,
        private TagMapper     $tagMapper,
    ) {}

    /**
     * @return OffsetPageResponseDTO<TagDTO>
     */
    public function searchPublic(SearchTagsDTO $dto): OffsetPageResponseDTO
    {
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($page, $size, $sort);
        $tags = $this->tagRepository->search($pageable);

        return OffsetPageResponseDTO::fromPaginator($tags,
            fn($t) => $this->tagMapper->toPublicDTO($t));
    }

    /**
     * @return PageResponseDTO<TagDTO>
     */
    public function search(SearchTagsDTO $dto): PageResponseDTO
    {
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($page, $size, $sort);
        $tags = $this->tagRepository->search($pageable);

        return PageResponseDTO::fromPaginator($tags,
            fn($t) => $this->tagMapper->toDTO($t));
    }

    public function create(CreateTagDTO $dto): TagDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $tag = $this->tagRepository->create($data);

            return $this->tagMapper->toDTO($tag);
        });
    }

    public function update(UpdateTagDTO $dto): TagDTO
    {
        $tag = $this->tagRepository->getByIdOrFail($dto->tagId);

        return DB::transaction(function () use ($tag, $dto) {
            $data = $this->prepareUpdateData($dto);
            $tag->update($data);
            $tag->refresh();

            return $this->tagMapper->toDTO($tag);
        });
    }

    public function delete(int $tagId): TagDTO
    {
        $tag = $this->tagRepository->getByIdOrFail($tagId);

        return DB::transaction(function () use ($tag) {
            $replica = $tag->replicate();

            $tag->products()->detach();
            $tag->delete();

            return $this->tagMapper->toDTO($replica);
        });

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

    private function getAuthUserId(): int
    {
        return Auth::id();
    }
}
