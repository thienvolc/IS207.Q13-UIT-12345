<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\AssignProductCategoriesDTO;
use Illuminate\Foundation\Http\FormRequest;

class AssignProductCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categories,category_id',
        ];
    }

    public function toDTO(int $productId): AssignProductCategoriesDTO
    {
        $v = $this->validated();

        return new AssignProductCategoriesDTO(
            productId: $productId,
            categoryIds: $v['category_ids'],
        );
    }
}
