<?php

namespace App\Domains\Order\DTOs\FormRequest;

use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class AdminSearchOrdersRequest extends FormRequest
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
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): AdminSearchOrdersDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'orders_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new AdminSearchOrdersDTO(
            query: get_string($v, 'query'),
            status: get_int($v, 'status'),
            userId: get_int($v, 'user_id'),
            start: get_int($v, 'start'),
            end: get_int($v, 'end'),
            min: get_int($v, 'min'),
            max: get_int($v, 'max'),
            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder
        );
    }
}
