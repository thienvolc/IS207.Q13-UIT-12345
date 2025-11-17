<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

use App\Domains\Catalog\DTOs\Tag\Commands\UpdateTagDTO;
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

    public function toDTO(int $tagId): UpdateTagDTO
    {
        $v = $this->validated();

        return new UpdateTagDTO(
            tagId: $tagId,
            title: $v['title'] ?? null,
            metaTitle: $v['meta_title'] ?? null,
            slug: $v['slug'] ?? null,
            desc: $v['desc'] ?? null,
        );
    }
}

