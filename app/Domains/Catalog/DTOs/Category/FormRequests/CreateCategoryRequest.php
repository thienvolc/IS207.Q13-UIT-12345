<?php

namespace App\Domains\Catalog\DTOs\Category\FormRequests;

use App\Domains\Catalog\DTOs\Category\Commands\CreateCategoryDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer|exists:categories,category_id',
            'level' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'desc' => 'nullable|string'
        ];
    }

    public function toDTO(): CreateCategoryDTO
    {
        $v = $this->validated();

        return new CreateCategoryDTO(
            parentId: int_or_null($v['parent_id'] ?? null),
            level: $v['level'],
            title: $v['title'],
            metaTitle: string_or_null($v['meta_title'] ?? null),
            slug: string_or_null($v['slug'] ?? null),
            desc: string_or_null($v['desc'] ?? null),
        );
    }
}

