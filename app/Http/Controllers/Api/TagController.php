<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Tag\FormRequests\CreateTagRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\SearchTagRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\UpdateTagRequest;
use App\Domains\Catalog\DTOs\Tag\Requests\CreateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetAllTagsDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\UpdateTagDTO;
use App\Domains\Catalog\Services\TagService;
use App\Http\Controllers\AppController;

class TagController extends AppController
{
    public function __construct(
        private readonly TagService $tagService
    )
    {
    }

    public function index(SearchTagRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetAllTagsDTO::fromArray([
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->tagService->searchPublic($dto);

        return $this->success($result);
    }

    public function searchAdmin(SearchTagRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetAllTagsDTO::fromArray([
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->tagService->search($dto);

        return $this->success($result);
    }

    public function store(CreateTagRequest $request): ResponseDTO
    {
        $dto = CreateTagDTO::fromArray($request->validated());

        $tag = $this->tagService->create($dto);

        return $this->created($tag);
    }

    public function update(UpdateTagRequest $request, int $tag_id): ResponseDTO
    {
        $dto = UpdateTagDTO::fromArray([
            'tag_id' => $tag_id,
            ...$request->validated()
        ]);

        $tag = $this->tagService->update($dto);

        return $this->success($tag);
    }

    public function destroy(int $tag_id): ResponseDTO
    {
        $tag = $this->tagService->delete($tag_id);

        return $this->success($tag);
    }
}
