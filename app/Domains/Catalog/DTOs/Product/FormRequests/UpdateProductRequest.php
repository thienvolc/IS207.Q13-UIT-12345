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
            title: string_or_null($v['title'] ?? null),
            metaTitle: string_or_null($v['meta_title'] ?? null),
            slug: string_or_null($v['slug'] ?? null),
            thumb: string_or_null($v['thumb'] ?? null),
            desc: string_or_null($v['desc'] ?? null),
            summary: string_or_null($v['summary'] ?? null),
            type: string_or_null($v['type'] ?? null),
            sku: string_or_null($v['sku'] ?? null),
            price: float_or_null($v['price'] ?? null),
            discount: int_or_null($v['discount'] ?? null),
            status: int_or_null($v['status'] ?? null) ?? 1,
            startsAt: string_or_null($v['starts_at'] ?? null),
            endsAt: string_or_null($v['ends_at'] ?? null),
        );
    }
}
