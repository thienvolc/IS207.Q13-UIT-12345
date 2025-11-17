<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

use App\Domains\Identity\DTOs\User\Commands\UpdateUserProfileDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'first_name' => 'nullable|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId, 'user_id'),
            ],
            'avatar' => 'nullable|string|max:255|url',
            'profile' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already in use by another user.',
            'avatar.url' => 'The avatar must be a valid URL.',
        ];
    }

    public function toDTO(): UpdateUserProfileDTO
    {
        $v = $this->validated();

        return new UpdateUserProfileDTO(
            firstName: string_or_null($v['first_name'] ?? null),
            middleName: string_or_null($v['middle_name'] ?? null),
            lastName: string_or_null($v['last_name'] ?? null),
            phone: string_or_null($v['phone'] ?? null),
        );
    }
}
