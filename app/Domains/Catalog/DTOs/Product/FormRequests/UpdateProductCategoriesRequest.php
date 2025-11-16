<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCategoriesRequest extends FormRequest
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
}
