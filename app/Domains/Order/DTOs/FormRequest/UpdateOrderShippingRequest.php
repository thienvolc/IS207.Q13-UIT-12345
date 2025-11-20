<?php

namespace App\Domains\Order\DTOs\FormRequest;

use App\Domains\Order\DTOs\Commands\UpdateOrderShippingDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'line1' => 'nullable|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function toDTO(int $orderId): UpdateOrderShippingDTO
    {
        $v = $this->validated();

        return new UpdateOrderShippingDTO(
            orderId: $orderId,
            firstName: get_string($v, 'first_name'),
            middleName: get_string($v, 'middle_name'),
            lastName: get_string($v, 'last_name'),
            phone: get_string($v, 'phone'),
            email: get_string($v, 'email'),
            line1: get_string($v, 'line1'),
            line2: get_string($v, 'line2'),
            city: get_string($v, 'city'),
            province: get_string($v, 'province'),
            country: get_string($v, 'country'),
        );
    }
}
