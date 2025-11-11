<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class SearchTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer|exists:users,user_id',
            'order_id' => 'nullable|integer|exists:orders,order_id',
            'status' => 'nullable|integer|in:1,2,3,4,5,6',
            'type' => 'nullable|integer|in:1,2',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => 'nullable|string|regex:/^[a-z_]+:(asc|desc)$/',
        ];
    }

    public function getPage(): int
    {
        return (int) $this->input('page', 1);
    }

    public function getSize(): int
    {
        return (int) $this->input('size', 10);
    }

    public function getSort(): array
    {
        $sort = $this->input('sort', 'created_at:desc');

        if (!str_contains($sort, ':')) {
            return ['created_at', 'desc'];
        }

        [$field, $order] = explode(':', $sort);
        return [$field, $order];
    }
}

