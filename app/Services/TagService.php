<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Product;
use App\Constants\ProductStatus;
use App\Constants\ResponseCode;
use App\Helpers\StringHelper;
use App\Http\Resources\TagResource;
use App\Http\Resources\ProductPublicResource;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TagService
{
    /**
     * Get all tags with pagination
     */
    public function getAllTags(int $offset = 0, int $limit = 10, string $sortField = 'created_at', string $sortOrder = 'desc'): array
    {
        $query = Tag::query();

        $totalCount = $query->count();

        $tags = $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => TagResource::collection($tags),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    /**
     * Create a new tag
     */
    public function createTag(array $data): array
    {
        $tag = DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $userId = Auth::id();
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $tag = Tag::create($data);

            return $tag;
        });

        return TagResource::transform($tag);
    }

    /**
     * Update a tag
     */
    public function updateTag(int $tagId, array $data): array
    {
        $tag = $this->getTagModel($tagId);

        $tag = DB::transaction(function () use ($tag, $data) {
            if (isset($data['title']) && empty($data['slug'])) {
                $data['slug'] = StringHelper::slugify($data['title']);
            }

            $data['updated_by'] = Auth::id();

            $tag->update($data);
            $tag->refresh();

            return $tag;
        });

        return TagResource::transform($tag);
    }

    /**
     * Delete a tag
     */
    public function deleteTag(int $tagId): array
    {
        $tag = $this->getTagModel($tagId);

        $deletedTag = DB::transaction(function () use ($tag) {
            // Detach all products before deleting
            $tag->products()->detach();

            $deletedTag = $tag->replicate();
            $tag->delete();

            return $deletedTag;
        });

        return TagResource::transform($deletedTag);
    }

    /**
     * Get products by tag ID
     */
    public function getProductsByTagId(int $tagId, int $offset = 0, int $limit = 10, string $sortField = 'created_at', string $sortOrder = 'desc'): array
    {
        $tag = $this->getTagModel($tagId);

        $query = $tag->products()
            ->where('status', ProductStatus::ACTIVE)
            ->with(['categories', 'tags', 'metas']);

        $totalCount = $query->count();

        $products = $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return [
            'data' => ProductPublicResource::collection($products),
            'limit' => $limit,
            'offset' => $offset,
            'total_count' => $totalCount,
            'has_more' => ($offset + $limit) < $totalCount,
        ];
    }

    /**
     * Get tag model by ID
     */
    private function getTagModel(int $tagId): Tag
    {
        $tag = Tag::find($tagId);

        if (!$tag) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $tag;
    }
}

