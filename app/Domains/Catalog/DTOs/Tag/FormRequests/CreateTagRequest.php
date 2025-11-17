<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

use App\Domains\Catalog\DTOs\Tag\Commands\CreateTagDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'desc' => 'nullable|string',
        ];
    }

    public function toDTO(): CreateTagDTO
    {
        $v = $this->validated();

        return new CreateTagDTO(
            title: $v['title'],
            metaTitle: $v['meta_title'] ?? null,
            slug: $v['slug'] ?? null,
            desc: $v['desc'] ?? null,
        );
    }
}

