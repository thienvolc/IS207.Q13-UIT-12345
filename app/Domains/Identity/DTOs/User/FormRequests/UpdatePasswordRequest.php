<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Commands\UpdatePasswordDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|max:255|different:old_password',
        ];
    }

    public function toDTO(): UpdatePasswordDTO
    {
        $v = $this->validated();

        return new UpdatePasswordDTO(
            oldPassword: $v['old_password'],
            newPassword: $v['new_password'],
        );
    }
}

