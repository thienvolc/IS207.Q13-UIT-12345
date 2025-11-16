<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'integer|exists:tags,tag_id',
        ];
    }
}
