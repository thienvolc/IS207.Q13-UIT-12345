<?php

namespace App\Domains\Blog\Mappers;

use App\Domains\Blog\DTOs\Responses\AdminBlogPostDTO;
use App\Domains\Blog\DTOs\Responses\PublicBlogPostDTO;
use App\Domains\Blog\Entities\BlogPost;

class BlogPostMapper
{
    public function toPublicDTO(BlogPost $post): PublicBlogPostDTO
    {
        return new PublicBlogPostDTO(
            blogpostId: $post->blogpost_id,
            title: $post->title,
            slug: $post->slug,
            thumb: $post->thumb,
            summary: $post->summary,
            content: $post->content,
            conclusion: $post->conclusion,
            publishedAt: $post->published_at?->toISOString(),
        );
    }

    public function toAdminDTO(BlogPost $post): AdminBlogPostDTO
    {
        return new AdminBlogPostDTO(
            blogpostId: $post->blogpost_id,
            title: $post->title,
            metaTitle: $post->meta_title,
            slug: $post->slug,
            thumb: $post->thumb,
            summary: $post->summary,
            content: $post->content,
            conclusion: $post->conclusion,
            status: $post->status,
            publishedAt: $post->published_at?->toISOString(),
            createdBy: $post->created_by,
            updatedBy: $post->updated_by,
            createdAt: $post->created_at?->toISOString(),
            updatedAt: $post->updated_at?->toISOString(),
        );
    }

    public function toPublicDTOs(iterable $posts): array
    {
        return array_map(fn($p) => $this->toPublicDTO($p), iterator_to_array($posts));
    }

    public function toAdminDTOs(iterable $posts): array
    {
        return array_map(fn($p) => $this->toAdminDTO($p), iterator_to_array($posts));
    }
}
