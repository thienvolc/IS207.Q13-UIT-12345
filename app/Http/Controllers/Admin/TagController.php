<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\Tag\CreateTagDto;
use App\Dtos\Tag\GetAllTagsDto;
use App\Dtos\Tag\UpdateTagDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Requests\Tag\SearchTagRequest;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends AppController
{
    public function __construct(
        private TagService $tagService
    ) {}

    public function index(SearchTagRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetAllTagsDto::fromArray([
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->tagService->getAllTags($dto);

        return $this->success($result);
    }

    public function store(CreateTagRequest $request): JsonResponse
    {
        $dto = CreateTagDto::fromArray($request->validated());

        $tag = $this->tagService->createTag($dto);

        return $this->created($tag);
    }

    public function update(UpdateTagRequest $request, int $tag_id): JsonResponse
    {
        $dto = UpdateTagDto::fromArray([
            'tagId' => $tag_id,
            ...$request->validated()
        ]);

        $tag = $this->tagService->updateTag($dto);

        return $this->success($tag);
    }

    public function destroy(int $tag_id): JsonResponse
    {
        $tag = $this->tagService->deleteTag($tag_id);

        return $this->success($tag);
    }
}
