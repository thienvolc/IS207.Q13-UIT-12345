<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Commands\UpdateUserStatusDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|integer|in:1,2,3,4',
        ];
    }

    public function toDTO(int $userId): UpdateUserStatusDTO
    {
        $v = $this->validated();

        return new UpdateUserStatusDTO(
            userId: $userId,
            status: (int)$v['status'],
        );
    }
}

