<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class GetOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|integer|in:1,2,3,4,5,6,7,8',
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
            'sort' => 'nullable|string|regex:/^[a-z_]+:(asc|desc)$/',
        ];
    }

    public function getOffset(): int
    {
        return (int) $this->input('offset', 0);
    }

    public function getLimit(): int
    {
        return (int) $this->input('limit', 10);
    }

    public function getSort(): array
    {
        $sort = $this->input('sort', 'orders_at:desc');

        if (!str_contains($sort, ':')) {
            return ['orders_at', 'desc'];
        }

        [$field, $order] = explode(':', $sort);
        return [$field, $order];
    }
}

