<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use App\Domains\Identity\DTOs\Auth\Commands\ResetPasswordDTO;
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

    public function toDTO(): ResetPasswordDTO
    {
        $v = $this->validated();

        return new ResetPasswordDTO(
            email: $v['email'],
            passwordResetToken: $v['password_reset_token'],
            newPassword: $v['new_password'],
        );
    }
}

