<?php

namespace App\Domains\Catalog\DTOs\Tag\FormRequests;

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
}

