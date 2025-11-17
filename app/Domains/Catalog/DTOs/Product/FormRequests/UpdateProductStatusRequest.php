<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\Constants\ProductStatus;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductStatusDTO;
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

    public function toDTO(int $productId): UpdateProductStatusDTO
    {
        $v = $this->validated();

        return new UpdateProductStatusDTO(
            productId: $productId,
            status: (int)$v['status'],
        );
    }
}
