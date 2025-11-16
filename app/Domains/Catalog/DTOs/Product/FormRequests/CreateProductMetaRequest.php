<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

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
}

