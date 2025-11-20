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
            'token' => 'required|string',
            'new_password' => 'required|string|min:8|max:255',
            'password_confirmation' => 'required|string|same:new_password',
        ];
    }

    public function toDTO(): ResetPasswordDTO
    {
        $v = $this->validated();

        return new ResetPasswordDTO(
            email: $v['email'],
            passwordResetToken: $v['token'],
            newPassword: $v['new_password'],
        );
    }
}

