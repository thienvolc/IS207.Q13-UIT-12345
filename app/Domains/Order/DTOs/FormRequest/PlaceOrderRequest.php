<?php

namespace App\Domains\Order\DTOs\FormRequest;

use App\Domains\Order\DTOs\Commands\PlaceOrderDTO;
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

    public function toDTO(): PlaceOrderDTO
    {
        $v = $this->validated();

        return new PlaceOrderDTO(
            cartId: (int)$v['cart_id'],
            promo: string_or_null($v['promo']),
        );
    }
}
