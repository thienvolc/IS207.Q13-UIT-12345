<?php

namespace App\Domains\Blog\DTOs\FormRequests;

use App\Domains\Blog\DTOs\Commands\UpdateBlogPostDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $blogpostId = $this->route('id');

        return [
            'title' => 'nullable|string|max:150',
            'meta_title' => 'nullable|string|max:150',
            'slug' => "nullable|string|max:150|unique:blog_posts,slug,{$blogpostId},blogpost_id",
            'thumb' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'conclusion' => 'nullable|string|max:255',
            'status' => 'nullable|integer|between:1,3',
            'published_at' => 'nullable|date',
        ];
    }

    public function toDTO(): UpdateBlogPostDTO
    {
        $v = $this->validated();

        return new UpdateBlogPostDTO(
            blogpostId: (int) $this->route('id'),
            title: get_string($v, 'title'),
            metaTitle: get_string($v, 'meta_title'),
            slug: get_string($v, 'slug'),
            thumb: get_string($v, 'thumb'),
            summary: get_string($v, 'summary'),
            content: get_string($v, 'content'),
            conclusion: get_string($v, 'conclusion'),
            status: get_int($v, 'status'),
            publishedAt: get_string($v, 'published_at'),
        );
    }
}
