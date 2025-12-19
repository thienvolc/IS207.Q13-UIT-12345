<?php

namespace App\Domains\Blog\DTOs\FormRequests;

use App\Domains\Blog\DTOs\Queries\AdminSearchBlogPostsDTO;
use Illuminate\Foundation\Http\FormRequest;

class AdminSearchBlogPostsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'q' => 'nullable|string|max:100',
            'status' => 'nullable|integer|between:1,3',
            'sort_field' => 'nullable|string|in:created_at,updated_at,published_at,title',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }

    public function toDTO(): AdminSearchBlogPostsDTO
    {
        $v = $this->validated();

        return new AdminSearchBlogPostsDTO(
            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            query: get_string($v, 'q'),
            status: get_int($v, 'status'),
            sortField: get_string($v, 'sort_field') ?? 'created_at',
            sortOrder: get_string($v, 'sort_order') ?? 'desc',
        );
    }
}
