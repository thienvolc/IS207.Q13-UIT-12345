<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|max:255|different:old_password',
        ];
    }
}

