<?php

namespace App\Domains\Inventory\DTOs\FormRequests;

use App\Domains\Inventory\DTOs\Commands\AdjustInventoryDTO;
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

    public function toDTO(int $productId): AdjustInventoryDTO
    {
        $v = $this->validated();

        return new AdjustInventoryDTO(
            productId: $productId,
            amount: $v['amount'],
            operationType: $v['operationType'],
            reason: $v['reason'] ?? null,
        );
    }
}

