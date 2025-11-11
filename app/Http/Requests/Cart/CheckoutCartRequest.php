<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'integer|exists:cart_items,cart_item_id',
        ];
    }
}

