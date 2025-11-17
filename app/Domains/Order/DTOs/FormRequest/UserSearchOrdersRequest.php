<?php

namespace App\Domains\Order\DTOs\FormRequest;

use App\Domains\Order\DTOs\Queries\UserSearchOrdersDTO;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Foundation\Http\FormRequest;

class UserSearchOrdersRequest extends FormRequest
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
            'sort' => ['nullable', 'string', 'regex:/^[a-z_]+:(asc|desc)$/'],
        ];
    }

    public function toDTO(): UserSearchOrdersDTO
    {
        $v = $this->validated();
        $sort = $v['sort'] ?? 'orders_at:desc';
        [$sortField, $sortOrder] = PaginationUtil::getSortFieldAndOrder($sort);

        return new UserSearchOrdersDTO(
            status: int_or_null($v['status']),
            offset: int_or_null($v['offset']) ?? 1,
            limit: int_or_null($v['limit']) ?? 10,
            sortField: $sortField,
            sortOrder: $sortOrder,
        );
    }
}
