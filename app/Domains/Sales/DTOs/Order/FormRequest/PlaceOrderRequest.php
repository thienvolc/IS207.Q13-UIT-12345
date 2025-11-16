<?php

namespace App\Domains\Sales\DTOs\Order\FormRequest;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_id' => 'required|integer|exists:carts,cart_id',
            'promo' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:150',
        ];
    }
}
