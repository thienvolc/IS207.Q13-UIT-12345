<?php

namespace App\Domains\Blog\DTOs\FormRequests;

use App\Domains\Blog\DTOs\Queries\PublicSearchBlogPostsDTO;
use Illuminate\Foundation\Http\FormRequest;

class PublicSearchBlogPostsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:50',
            'q' => 'nullable|string|max:100',
            'sort_field' => 'nullable|string|in:published_at,title,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }

    public function toDTO(): PublicSearchBlogPostsDTO
    {
        $v = $this->validated();

        return new PublicSearchBlogPostsDTO(
            offset: get_int($v, 'offset') ?? 0,
            limit: get_int($v, 'limit') ?? 10,
            query: get_string($v, 'q'),
            sortField: get_string($v, 'sort_field') ?? 'published_at',
            sortOrder: get_string($v, 'sort_order') ?? 'desc',
        );
    }
}
