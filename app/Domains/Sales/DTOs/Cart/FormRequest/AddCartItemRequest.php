<?php

namespace App\Domains\Sales\DTOs\Cart\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1|max:9999',
        ];
    }
}
