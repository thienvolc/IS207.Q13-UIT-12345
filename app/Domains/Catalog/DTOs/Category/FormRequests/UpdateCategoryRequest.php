<?php

namespace App\Domains\Catalog\DTOs\Category\FormRequests;

use App\Domains\Catalog\DTOs\Category\Commands\UpdateCategoryDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category_id');

        return [
            'parent_id' => 'nullable|integer|exists:categories,category_id',
            'level' => 'sometimes|required|integer|min:1',
            'title' => 'sometimes|required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $categoryId . ',category_id',
            'desc' => 'nullable|string'
        ];
    }

    public function toDTO(int $categoryId): UpdateCategoryDTO
    {
        $v = $this->validated();

        return new UpdateCategoryDTO(
            categoryId: $categoryId,
            parentId: int_or_null($v['parent_id'] ?? null),
            level: int_or_null($v['level'] ?? null),
            title: string_or_null($v['title'] ?? null),
            metaTitle: string_or_null($v['meta_title'] ?? null),
            slug: string_or_null($v['slug'] ?? null),
            desc: string_or_null($v['desc'] ?? null),
        );
    }
}

