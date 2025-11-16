<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Entities\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagRepository
{
    public function findById(int $tagId): ?Tag
    {
        return Tag::find($tagId);
    }

    public function findAll(string $sortField, string $sortOrder, int $offset, int $limit): Collection
    {
        return Tag::query()
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function count(): int
    {
        return Tag::count();
    }

    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    public function update(Tag $tag, array $data): bool
    {
        return $tag->update($data);
    }

    public function delete(Tag $tag): bool
    {
        return $tag->delete();
    }

    public function detachAllProducts(Tag $tag): void
    {
        $tag->products()->detach();
    }
}
