<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductDTO;
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

    public function toDTO(int $productId): UpdateProductDTO
    {

        $v = $this->validated();

        return new UpdateProductDTO(
            productId: $productId,
            title: get_string($v, 'title'),
            metaTitle: get_string($v, 'meta_title'),
            slug: get_string($v, 'slug'),
            thumb: get_string($v, 'thumb'),
            desc: get_string($v, 'desc'),
            summary: get_string($v, 'summary'),
            type: get_string($v, 'type'),
            sku: get_string($v, 'sku'),
            price: get_float($v, 'price'),
            discount: get_int($v, 'discount'),
            status: get_int($v, 'status') ?? 1,
            startsAt: get_string($v, 'starts_at'),
            endsAt: get_string($v, 'ends_at'),
        );
    }
}
