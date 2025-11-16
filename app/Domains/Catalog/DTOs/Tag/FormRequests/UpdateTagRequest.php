<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tagId = $this->route('tag_id');

        return [
            'title' => 'sometimes|required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tagId . ',tag_id',
            'desc' => 'nullable|string',
        ];
    }
}

