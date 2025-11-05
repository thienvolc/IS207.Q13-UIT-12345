<?php

namespace App\Http\Requests\Category;

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
            'desc' => 'nullable|string',
            'children' => 'nullable|array',
            'children.*' => 'integer|exists:categories,category_id',
        ];
    }
}

