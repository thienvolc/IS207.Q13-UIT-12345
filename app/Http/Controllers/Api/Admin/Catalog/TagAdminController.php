<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Tag\FormRequests\CreateTagRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\SearchTagsRequest;
use App\Domains\Catalog\DTOs\Tag\FormRequests\UpdateTagRequest;
use App\Domains\Catalog\Services\TagService;
use App\Http\Controllers\AppController;

class TagAdminController extends AppController
{
    public function __construct(
        private readonly TagService $tagService
    ) {}

    /**
     * [GET] /api/admin/tags
     */
    public function searchAdmin(SearchTagsRequest $req): ResponseDTO
    {
        $result = $this->tagService->search($req->toDTO());
        return $this->success($result);
    }

    /**
     * [POST] /api/admin/tags
     */
    public function store(CreateTagRequest $req): ResponseDTO
    {
        $tag = $this->tagService->create($req->toDTO());
        return $this->created($tag);
    }

    /**
     * [PUT] /api/admin/tags/{tag_id}
     */
    public function update(UpdateTagRequest $req, int $tag_id): ResponseDTO
    {
        $tag = $this->tagService->update($req->toDTO($tag_id));
        return $this->success($tag);
    }

    /**
     * [DELETE] /api/admin/tags/{tag_id}
     */
    public function destroy(int $tag_id): ResponseDTO
    {
        $tag = $this->tagService->delete($tag_id);
        return $this->success($tag);
    }
}
