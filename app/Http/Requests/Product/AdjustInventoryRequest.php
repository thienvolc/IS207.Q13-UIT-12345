<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:1',
            'operationType' => ['required', 'string', Rule::in(['increase', 'decrease'])],
            'reason' => 'nullable|string|max:500',
        ];
    }
}

