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
            phone: string_or_null($v['phone'] ?? null),
            firstName: string_or_null($v['first_name'] ?? null),
            middleName: string_or_null($v['middle_name'] ?? null),
            lastName: string_or_null($v['last_name'] ?? null),
            isAdmin: (bool)($v['is_admin']),
            roles: $v['roles'] ?? [],
        );
    }
}

