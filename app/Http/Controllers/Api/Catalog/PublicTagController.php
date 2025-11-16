<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Domains\Catalog\DTOs\Product\FormRequests\SearchProductRequest;
use App\Domains\Catalog\DTOs\Tag\Requests\GetProductsByTagDTO;
use App\Domains\Catalog\Services\TagService;
use App\Http\Controllers\AppController;
use App\Applications\DTOs\Responses\ResponseDTO;

class PublicTagController extends AppController
{
    public function __construct(
        private TagService $tagService
    )
    {
    }

    public function products(SearchProductRequest $request, int $tag_id): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = GetProductsByTagDto::fromArray([
            'tagId' => $tag_id,
            'offset' => $request->getOffset(),
            'limit' => $request->getLimit(),
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $result = $this->tagService->getProductsByTagId($dto);

        return $this->success($result);
    }
}
