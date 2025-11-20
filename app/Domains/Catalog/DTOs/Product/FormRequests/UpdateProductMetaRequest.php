<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductMetaDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductMetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ];
    }

    public function toDTO(int $productId, int $metaId): UpdateProductMetaDTO
    {
        $v = $this->validated();

        return new UpdateProductMetaDTO(
            productId: $productId,
            metaId: $metaId,
            key: get_string($v, 'key'),
            content: get_string($v, 'content'),
        );
    }
}

