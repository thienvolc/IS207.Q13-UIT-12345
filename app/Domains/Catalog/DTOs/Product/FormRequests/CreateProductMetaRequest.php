<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\CreateProductMetaDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductMetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function toDTO(int $productId): CreateProductMetaDTO
    {
        $v = $this->validated();

        return new CreateProductMetaDTO(
            productId: $productId,
            key: $v['key'],
            content: $v['content'],
        );
    }
}

