<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use App\Domains\Identity\DTOs\Auth\Commands\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
            'password' => 'required|string',
        ];
    }

    public function toDTO(): LoginDTO
    {
        $v = $this->validated();

        return new LoginDTO(
            email: $v['email'],
            password: $v['password'],
        );
    }
}

