<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\AppController;
use App\Http\Requests\Product\SearchProductRequest;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends AppController
{
    public function __construct(
        private TagService $tagService
    ) {}

    /**
     * GET /tags/{tag_id}/products
     */
    public function products(SearchProductRequest $request, int $tag_id): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $result = $this->tagService->getProductsByTagId(
            $tag_id,
            $request->getOffset(),
            $request->getLimit(),
            $sortField,
            $sortOrder
        );

        return $this->success($result);
    }
}

