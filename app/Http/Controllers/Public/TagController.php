<?php

namespace App\Http\Controllers\Public;

use App\Dtos\Tag\GetProductsByTagDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Product\SearchProductRequest;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends AppController
{
    public function __construct(
        private TagService $tagService
    ) {}

    public function products(SearchProductRequest $request, int $tag_id): JsonResponse
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
