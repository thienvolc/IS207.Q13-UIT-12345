<?php

namespace App\Domains\Blog\Services;

use App\Domains\Blog\Constants\BlogPostStatus;
use App\Domains\Blog\DTOs\Commands\CreateBlogPostDTO;
use App\Domains\Blog\DTOs\Commands\UpdateBlogPostDTO;
use App\Domains\Blog\DTOs\Queries\AdminSearchBlogPostsDTO;
use App\Domains\Blog\DTOs\Queries\PublicSearchBlogPostsDTO;
use App\Domains\Blog\DTOs\Responses\AdminBlogPostDTO;
use App\Domains\Blog\DTOs\Responses\PublicBlogPostDTO;
use App\Domains\Blog\Mappers\BlogPostMapper;
use App\Domains\Blog\Repositories\BlogPostRepository;
use App\Domains\Common\DTOs\OffsetPageResponseDTO;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Infra\Helpers\StringHelper;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\PaginationUtil;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

readonly class BlogPostService
{
    public function __construct(
        private BlogPostRepository $repository,
        private BlogPostMapper $mapper,
    ) {
    }

    public function searchPublic(PublicSearchBlogPostsDTO $dto): OffsetPageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $page = PaginationUtil::offsetToPage($dto->offset, $dto->limit);
        $pageable = Pageable::of($page, $dto->limit, $sort);

        $posts = $this->repository->searchPublic($pageable, $dto->query);

        return OffsetPageResponseDTO::fromPaginator(
            $posts,
            fn($p) => $this->mapper->toPublicDTO($p)
        );
    }

    public function getBySlug(string $slug): PublicBlogPostDTO
    {
        $post = $this->repository->getBySlugOrFail($slug);
        return $this->mapper->toPublicDTO($post);
    }

    public function search(AdminSearchBlogPostsDTO $dto): PageResponseDTO
    {
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $posts = $this->repository->search($pageable, $dto->query, $dto->status);

        return PageResponseDTO::fromPaginator(
            $posts,
            fn($p) => $this->mapper->toAdminDTO($p)
        );
    }

    public function getById(int $blogpostId): AdminBlogPostDTO
    {
        $post = $this->repository->getByIdOrFail($blogpostId);
        return $this->mapper->toAdminDTO($post);
    }

    public function create(CreateBlogPostDTO $dto): AdminBlogPostDTO
    {
        return DB::transaction(function () use ($dto) {
            $data = $this->prepareCreateData($dto);
            $post = $this->repository->create($data);
            return $this->mapper->toAdminDTO($post);
        });
    }

    public function update(UpdateBlogPostDTO $dto): AdminBlogPostDTO
    {
        $post = $this->repository->getByIdOrFail($dto->blogpostId);

        return DB::transaction(function () use ($post, $dto) {
            $data = $this->prepareUpdateData($dto);
            $post->update($data);
            $post->refresh();
            return $this->mapper->toAdminDTO($post);
        });
    }

    public function delete(int $blogpostId): AdminBlogPostDTO
    {
        $post = $this->repository->getByIdOrFail($blogpostId);

        return DB::transaction(function () use ($post) {
            $replica = clone $post;
            $this->repository->delete($post);
            return $this->mapper->toAdminDTO($replica);
        });
    }

    public function publish(int $blogpostId): AdminBlogPostDTO
    {
        $post = $this->repository->getByIdOrFail($blogpostId);

        return DB::transaction(function () use ($post) {
            $post->update([
                'status' => BlogPostStatus::PUBLISHED,
                'published_at' => $post->published_at ?? now(),
                'updated_by' => $this->userId(),
            ]);
            $post->refresh();
            return $this->mapper->toAdminDTO($post);
        });
    }

    public function getPostsByCurrentUser(): array
    {
        $userId = $this->userId();
        
        // Lấy bài viết của user hiện tại
        $posts = $this->repository->getByUserId($userId);
        
        return $posts->map(
            fn($post) => $this->mapper->toAdminDTO($post)
        )->toArray();
    }

    private function prepareCreateData(CreateBlogPostDTO $dto): array
    {
        $userId = $this->userId();
        $data = $dto->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        return $data;
    }

    private function prepareUpdateData(UpdateBlogPostDTO $dto): array
    {
        $userId = $this->userId();
        $data = array_filter($dto->toArray(), fn($value) => $value !== null);

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = StringHelper::slugify($dto->title);
        }

        $data['updated_by'] = $userId;

        return $data;
    }

    private function userId(): int
    {
        return Auth::id() ?? 1;
    }
}
