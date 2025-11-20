<?php

namespace App\Domains\Transaction\DTOs\FormRequest;

use App\Domains\Order\DTOs\Queries\AdminSearchOrdersDTO;
use App\Domains\Transaction\DTOs\Queries\SearchTransactionsDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
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
            'query' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer|exists:users,user_id',
            'order_id' => 'nullable|integer|exists:orders,order_id',
            'status' => 'nullable|integer|in:1,2,3,4,5,6',
            'type' => 'nullable|integer|in:1,2',

            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
            'min' => 'nullable|numeric|min:0',
            'max' => 'nullable|numeric|min:0|gte:min',

            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): SearchTransactionsDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'created_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new SearchTransactionsDTO(
            query: get_string($v, 'query'),
            userId: get_int($v, 'user_id'),
            orderId: get_int($v, 'order_id'),
            status: get_int($v, 'status'),
            type: get_int($v, 'type'),

            start: get_string($v, 'start'),
            end: get_string($v, 'end'),
            min: get_float($v, 'min'),
            max: get_float($v, 'max'),

            page: get_int($v, 'page') ?? 1,
            size: get_int($v, 'size') ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder
        );
    }
}
