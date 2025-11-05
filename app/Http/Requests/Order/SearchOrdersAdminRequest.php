<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class SearchOrdersAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',
            'status' => 'nullable|integer|in:1,2,3,4,5,6,7,8',
            'user_id' => 'nullable|integer|exists:users,user_id',
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
            'min' => 'nullable|numeric|min:0',
            'max' => 'nullable|numeric|min:0|gte:min',
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
        $sort = $this->input('sort', 'orders_at:desc');

        if (!str_contains($sort, ':')) {
            return ['orders_at', 'desc'];
        }

        [$field, $order] = explode(':', $sort);
        return [$field, $order];
    }
}

