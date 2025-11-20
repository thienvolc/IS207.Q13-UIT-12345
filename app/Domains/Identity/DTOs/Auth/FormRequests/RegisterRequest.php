<?php

namespace App\Domains\Identity\DTOs\Auth\FormRequests;

use App\Domains\Identity\DTOs\Auth\Commands\RegisterDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|max:255',
            'first_name' => 'required|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'required|string|max:150',
        ];
    }

    public function toDTO(): RegisterDTO
    {
        $v = $this->validated();

        return new RegisterDTO(
            email: $v['email'],
            password: $v['password'],
            phone: get_string($v, 'phone'),
            firstName: get_string($v, 'first_name'),
            middleName: get_string($v, 'middle_name'),
            lastName: get_string($v, 'last_name'),
        );
    }
}
