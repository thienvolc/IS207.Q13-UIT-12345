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
            meta_title: get_string($v, 'meta_title'),
            slug: get_string($v, 'slug'),
            thumb: get_string($v, 'thumb'),
            desc: get_string($v, 'desc'),
            summary: get_string($v, 'summary'),
            type: get_string($v, 'type'),
            sku: get_string($v, 'sku'),
            price: (float)$v['price'],
            discount: get_int($v, 'discount'),
            quantity: get_int($v, 'quantity') ?? 0,
            status: get_int($v, 'status') ?? 1,
            starts_at: get_string($v, 'starts_at'),
            ends_at: get_string($v, 'ends_at'),
        );
    }
}
