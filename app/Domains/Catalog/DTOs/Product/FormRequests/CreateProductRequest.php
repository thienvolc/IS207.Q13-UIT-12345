<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,category_id',
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'thumb' => 'nullable|url|max:500',
            'desc' => 'nullable|string',
            'summary' => 'nullable|string|max:500',
            'type' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'price' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ];
    }
}
