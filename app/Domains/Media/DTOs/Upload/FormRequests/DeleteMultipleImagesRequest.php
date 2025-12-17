<?php

namespace App\Domains\Media\DTOs\Upload\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMultipleImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'public_ids' => 'required|array|min:1',
            'public_ids.*' => 'required|string',
        ];
    }
}
