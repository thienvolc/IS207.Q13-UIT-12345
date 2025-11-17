<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\CreateProductDTO;
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
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'thumb' => 'nullable|url|max:500',
            'desc' => 'nullable|string',
            'summary' => 'nullable|string|max:500',
            'type' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'nullable|integer|in:0,1,2',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ];
    }

    public function toDTO(): CreateProductDTO
    {
        $v = $this->validated();

        return new CreateProductDTO(
            title: $v['title'],
            meta_title: string_or_null($v['meta_title'] ?? null),
            slug: string_or_null($v['slug'] ?? null),
            thumb: string_or_null($v['thumb'] ?? null),
            desc: string_or_null($v['desc'] ?? null),
            summary: string_or_null($v['summary'] ?? null),
            type: string_or_null($v['type'] ?? null),
            sku: string_or_null($v['sku'] ?? null),
            price: (float)$v['price'],
            discount: int_or_null($v['discount'] ?? null),
            quantity: int_or_null($v['quantity'] ?? null) ?? 0,
            status: int_or_null($v['status'] ?? null) ?? 1,
            starts_at: string_or_null($v['starts_at'] ?? null),
            ends_at: string_or_null($v['ends_at'] ?? null),
        );
    }
}
