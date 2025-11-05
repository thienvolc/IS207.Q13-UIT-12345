<?php

namespace App\Http\Requests\Category;

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
            'desc' => 'nullable|string',
            'children' => 'nullable|array',
            'children.*' => 'integer|exists:categories,category_id',
        ];
    }
}

