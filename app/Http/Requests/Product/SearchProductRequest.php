<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductRequest extends FormRequest
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
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
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
