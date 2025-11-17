<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

use App\Domains\Catalog\DTOs\Tag\Queries\SearchTagsDTO;
use Illuminate\Foundation\Http\FormRequest;

class SearchTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): SearchTagsDTO
    {
        $v = $this->validated();

        return new SearchTagsDTO(
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
        );
    }
}

