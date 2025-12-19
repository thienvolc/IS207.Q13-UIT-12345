<?php

namespace App\Domains\Blog\Repositories;

use App\Domains\Blog\Constants\BlogPostStatus;
use App\Domains\Blog\Entities\BlogPost;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogPostRepository
{
    public function searchPublic(Pageable $pageable, ?string $query = null): LengthAwarePaginator
    {
        $qb = BlogPost::query()
            ->where('status', BlogPostStatus::PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });

        if ($query) {
            $qb->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%");
            });
        }

        return $qb->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function search(Pageable $pageable, ?string $query = null, ?int $status = null): LengthAwarePaginator
    {
        $qb = BlogPost::query();

        if ($query) {
            $qb->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%");
            });
        }

        if ($status !== null) {
            $qb->where('status', $status);
        }

        return $qb->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function getBySlugOrFail(string $slug): BlogPost
    {
        return BlogPost::query()
            ->where('slug', $slug)
            ->where('status', BlogPostStatus::PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getByIdOrFail(int $blogpostId): BlogPost
    {
        return BlogPost::query()
            ->where('blogpost_id', $blogpostId)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }

    public function getByUserId(int $userId)
    {
        return BlogPost::query()
            ->where('created_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllPosts()
    {
        return BlogPost::query()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data): BlogPost
    {
        return BlogPost::create($data);
    }

    public function delete(BlogPost $post): void
    {
        $post->delete();
    }
}
