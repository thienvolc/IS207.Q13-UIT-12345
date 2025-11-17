<?php

namespace App\Domains\Order\DTOs\FormRequest;

use App\Domains\Order\DTOs\Commands\UpdateOrderStatusDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|integer|in:1,2,3,4,5,6,7,8',
        ];
    }

    public function toDTO(int $orderId): UpdateOrderStatusDTO
    {
        $v = $this->validated();

        return new UpdateOrderStatusDTO(
            orderId: $orderId,
            status: (int)$v['status'],
        );
    }
}
