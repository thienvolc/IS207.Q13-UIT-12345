<?php

namespace App\Domains\Catalog\Services;

use App\Applications\DTOs\Responses\PageResponseDTO;
use App\Domains\Catalog\DTOs\Product\Responses\ProductAdminResponseDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\CreateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetAllTagsDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\UpdateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Responses\TagResponseDTO;
use App\Domains\Catalog\Repositories\TagRepository;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class TagService
{
    public function __construct(
        private TagRepository $tagRepository
    ) {}

    /** @return PageResponseDTO<ProductAdminResponseDTO> */
    public function searchPublic(GetAllTagsDTO $dto): PageResponseDTO
    {
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $size = $dto->limit;
        $pageable = Pageable::of($page, $size);
        $products = $this->tagRepository->searchPublic($pageable);
        return PageResponseDTO::fromPaginator($products);
    }

    public function create(CreateTagDTO $dto): TagResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $tag = $this->tagRepository->create($data);

            return TagResponseDTO::fromModel($tag);
        });
    }

    public function update(UpdateTagDTO $dto): TagResponseDTO
    {
        $tag = $this->tagRepository->getByIdOrFail($dto->tagId);

        return DB::transaction(function () use ($tag, $dto) {
            $data = $this->prepareUpdateData($dto);
            $tag->update($data);
            $tag->refresh();

            return TagResponseDTO::fromModel($tag);
        });
    }

    public function delete(int $tagId): array
    {
        $tag = $this->tagRepository->getByIdOrFail($tagId);

        return DB::transaction(function () use ($tag) {
            $replica = $tag->replicate();

            $tag->products()->detach();
            $tag->delete();

            return TagResponseDTO::fromModel($replica);
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
