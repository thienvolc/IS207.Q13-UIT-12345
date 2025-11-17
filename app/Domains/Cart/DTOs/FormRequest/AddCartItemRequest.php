<?php

namespace App\Domains\Cart\DTOs\FormRequest;

use App\Domains\Cart\DTOs\Commands\AddCartItemDTO;
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

    public function toDTO(): AddCartItemDTO
    {
        $v = $this->validated();

        return new AddCartItemDTO(
            productId: (int)$v['product_id'],
            quantity: (int)$v['quantity'],
        );
    }
}
