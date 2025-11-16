<?php

namespace App\Domains\Identity\DTOs\User\FormRequests;

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
}

