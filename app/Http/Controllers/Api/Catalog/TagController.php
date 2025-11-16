<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Domains\Catalog\DTOs\Tag\FormRequests\CreateTagRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\SearchTagRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\UpdateTagRequest;
use App\Domains\Catalog\DTOs\Tag\Requests\CreateTagDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\GetAllTagsDTO;
use App\Domains\Catalog\DTOs\Tag\Requests\UpdateTagDTO;
use App\Domains\Catalog\Services\TagService;
use App\Http\Controllers\AppController;
use App\Applications\DTOs\Responses\ResponseDTO;

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

        $result = $this->tagService->getAllTags($dto);

        return $this->success($result);
    }

    public function store(CreateTagRequest $request): ResponseDTO
    {
        $dto = CreateTagDTO::fromArray($request->validated());

        $tag = $this->tagService->createTag($dto);

        return $this->created($tag);
    }

    public function update(UpdateTagRequest $request, int $tag_id): ResponseDTO
    {
        $dto = UpdateTagDTO::fromArray([
            'tagId' => $tag_id,
            ...$request->validated()
        ]);

        $tag = $this->tagService->updateTag($dto);

        return $this->success($tag);
    }

    public function destroy(int $tag_id): ResponseDTO
    {
        $tag = $this->tagService->deleteTag($tag_id);

        return $this->success($tag);
    }
}
