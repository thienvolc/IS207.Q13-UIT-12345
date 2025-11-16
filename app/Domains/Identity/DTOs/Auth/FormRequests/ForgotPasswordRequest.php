<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
        ];
    }
}

