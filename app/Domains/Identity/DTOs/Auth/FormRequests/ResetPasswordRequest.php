<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
            'password_reset_token' => 'required|string',
            'new_password' => 'required|string|min:8|max:255',
        ];
    }
}

