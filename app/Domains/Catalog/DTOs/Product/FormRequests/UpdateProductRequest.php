<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'category_id' => 'nullable|integer|exists:categories,category_id',
            'title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId . ',product_id',
            'thumb' => 'nullable|url|max:500',
            'desc' => 'nullable|string',
            'summary' => 'nullable|string|max:500',
            'type' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $productId . ',product_id',
            'price' => 'nullable|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ];
    }
}
