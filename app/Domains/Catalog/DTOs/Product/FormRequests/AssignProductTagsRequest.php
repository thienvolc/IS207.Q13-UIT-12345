<?php

namespace App\Domains\Catalog\DTOs\Product\FormRequests;

use App\Domains\Catalog\DTOs\Product\Commands\AssignProductTagsDTO;
use Illuminate\Foundation\Http\FormRequest;

class AssignProductTagsRequest extends FormRequest
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

    public function toDTO(int $productId): AssignProductTagsDTO
    {
        $v = $this->validated();

        return new AssignProductTagsDTO(
            productId: $productId,
            tagIds: $v['tag_ids'],
        );
    }
}
