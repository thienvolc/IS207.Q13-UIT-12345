<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use App\Domains\Identity\DTOs\Auth\Commands\SendPasswordResetDTO;
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

    public function toDTO(): SendPasswordResetDTO
    {
        $v = $this->validated();

        return new SendPasswordResetDTO(
            email: $v['email'],
        );
    }
}

