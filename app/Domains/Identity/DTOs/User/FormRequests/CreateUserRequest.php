<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Commands\CreateUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'is_admin' => 'nullable|boolean',
            'roles' => 'nullable|array',
            'roles.*.role_id' => 'required|integer|exists:roles,role_id',
        ];
    }

    public function toDTO(): CreateUserDTO
    {
        $v = $this->validated();

        return new CreateUserDTO(
            email: $v['email'],
            password: $v['password'],
            phone: get_string($v, 'phone'),
            firstName: get_string($v, 'first_name'),
            middleName: get_string($v, 'middle_name'),
            lastName: get_string($v, 'last_name'),
            isAdmin: get_bool($v, 'is_admin') ?? false,
            roles: $v['roles'] ?? [],
        );
    }
}

