<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Commands\AssignRolesDTO;
use Illuminate\Foundation\Http\FormRequest;

class AssignRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => 'required|array|min:1',
            'roles.*.role_id' => 'required|integer|exists:roles,role_id',
        ];
    }

    public function toDTO(int $userId): AssignRolesDTO
    {
        $v = $this->validated();

        return new AssignRolesDTO(
            userId: $userId,
            roleIds: $v['roles'],
        );
    }
}

