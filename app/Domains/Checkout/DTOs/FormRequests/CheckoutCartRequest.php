<?php

namespace App\Domains\Checkout\DTOs\FormRequests;

use App\Domains\Checkout\DTOs\Commands\CheckoutCartDTO;
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

    public function toDTO(): CheckoutCartDTO
    {
        $v = $this->validated();

        return new CheckoutCartDTO(
            items: $v['items'],
            firstName: $v['first_name'],
            middleName: $v['middle_name'] ?? null,
            lastName: $v['last_name'],
            phone: $v['phone'],
            email: $v['email'],
            line1: $v['line1'],
            line2: $v['line2'] ?? null,
            city: $v['city'],
            province: $v['province'],
            country: $v['country'],
            note: $v['note'] ?? null,
        );
    }
}
