<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSearchProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,category_id',
            'price_min' => 'nullable|integer|min:0',
            'price_max' => 'nullable|integer|min:0|gte:price_min',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function getPage(): int
    {
        return (int)$this->input('page', 1);
    }

    public function getSize(): int
    {
        return (int)$this->input('size', 10);
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
