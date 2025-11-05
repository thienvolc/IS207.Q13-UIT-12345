<?php

namespace App\Http\Controllers\Admin;

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

    /**
     * GET /admin/tags
     */
    public function index(SearchTagRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->tagService->getAllTags(
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }

    /**
     * POST /admin/tags
     */
    public function store(CreateTagRequest $request): JsonResponse
    {
        $tag = $this->tagService->createTag($request->validated());

        return $this->createdResponse($tag);
    }

    /**
     * PUT /admin/tags/{tag_id}
     */
    public function update(UpdateTagRequest $request, int $tag_id): JsonResponse
    {
        $tag = $this->tagService->updateTag($tag_id, $request->validated());

        return $this->success($tag);
    }

    /**
     * DELETE /admin/tags/{tag_id}
     */
    public function destroy(int $tag_id): JsonResponse
    {
        $tag = $this->tagService->deleteTag($tag_id);

        return $this->success($tag);
    }
}

