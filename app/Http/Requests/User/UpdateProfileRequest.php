<?php

namespace App\Http\Requests\User;

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
}
