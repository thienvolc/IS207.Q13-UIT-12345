<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

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
}

