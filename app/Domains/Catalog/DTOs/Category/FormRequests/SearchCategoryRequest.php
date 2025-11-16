<?php

namespace App\Domains\Catalog\DTOs\Category\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class SearchCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'level' => 'required|integer|min:1',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => 'nullable|string|regex:/^[a-z_]+:(asc|desc)$/',
        ];
    }

    public function getOffset(): int
    {
        return (int) $this->input('offset', 0);
    }

    public function getLimit(): int
    {
        return (int) $this->input('limit', 10);
    }

    public function getPage(): int
    {
        return (int) $this->input('page', 1);
    }

    public function getSize(): int
    {
        return (int) $this->input('size', 10);
    }

    public function getSort(): array
    {
        $sort = $this->input('sort', 'created_at:desc');

        if (!str_contains($sort, ':')) {
            return ['created_at', 'desc'];
        }

        [$field, $order] = explode(':', $sort);
        return [$field, $order];
    }
}
