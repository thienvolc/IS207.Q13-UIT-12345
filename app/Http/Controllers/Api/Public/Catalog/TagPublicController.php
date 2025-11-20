<?php

namespace App\Http\Controllers\Api\Public\Catalog;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Catalog\DTOs\Tag\FormRequests\SearchTagsRequest;
use App\Domains\Catalog\Services\TagService;
use App\Http\Controllers\AppController;

class TagPublicController extends AppController
{
    public function __construct(
        private readonly TagService $tagService
    ) {}

    /**
     * [GET] /api/tags
     */
    public function search(SearchTagsRequest $req): ResponseDTO
    {
        $result = $this->tagService->searchPublic($req->toDTO());
        return $this->success($result);
    }
}
