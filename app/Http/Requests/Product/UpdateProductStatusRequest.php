<?php

namespace App\Http\Requests\Product;

use App\Constants\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'integer',
                Rule::in([
                    ProductStatus::ACTIVE,
                    ProductStatus::OUT_OF_STOCK,
                    ProductStatus::INACTIVE,
                    ProductStatus::DISCONTINUED,
                    ProductStatus::ARCHIVE,
                ])
            ],
        ];
    }
}
