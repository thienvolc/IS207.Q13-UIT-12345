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
            parentId: get_int($v, 'parent_id'),
            level: get_int($v, 'level'),
            title: get_string($v, 'title'),
            metaTitle: get_string($v, 'meta_title'),
            slug: get_string($v, 'slug'),
            desc: get_string($v, 'desc'),
        );
    }
}

