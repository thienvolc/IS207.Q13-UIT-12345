<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Entities\Tag;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Pagination\LengthAwarePaginator;

class TagRepository
{
    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    public function getByIdOrFail(int $tagId): ?Tag
    {
        return Tag::find($tagId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function searchPublic(Pageable $pageable): LengthAwarePaginator
    {
        return Tag::query()
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }
}
