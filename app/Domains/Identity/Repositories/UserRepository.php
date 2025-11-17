<?php

namespace App\Domains\Identity\Repositories;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Identity\DTOs\User\Queries\UserFilter;
use App\Domains\Identity\Entities\User;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function findByIdWithRolesOrFail(int $userId): ?User
    {
        return User::with(['profile', 'roles'])->find($userId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function getByIdOrFail(int $authId): ?User
    {
        return User::with('profile')->find($authId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByEmailWithProfile(string $email): ?User
    {
        return User::where('email', $email)->with('profile')->first();
    }

    public function existsByEmail(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function existsByPhone(string $phone): bool
    {
        return User::where('phone', $phone)->exists();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function updatePasswordByEmail(string $email, string $hashedPassword): int
    {
        return User::where('email', $email)->update(['password' => $hashedPassword]);
    }

    public function searchUsers(Pageable $pageable, UserFilter $filters): LengthAwarePaginator
    {
        $query = User::query()->with(['profile', 'roles']);

        $this->applyFilters($query, $filters);

        return $query
            ->orderBy($pageable->sort->by, $pageable->sort->order)
            ->paginate($pageable->size, ['*'], 'page', $pageable->page);
    }

    public function searchWithFilters(array $filters, string $sortField, string $sortOrder, int $offset, int $limit): Collection
    {
        $query = User::query()->with(['profile', 'roles']);

        $this->applyFilters($query, $filters);

        return $query->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function countWithFilters(array $filters): int
    {
        $query = User::query();

        $this->applyFilters($query, $filters);

        return $query->count();
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['query'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('email', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('phone', 'like', '%' . $filters['query'] . '%')
                    ->orWhereHas('profile', function ($subQ) use ($filters) {
                        $subQ->where('first_name', 'like', '%' . $filters['query'] . '%')
                            ->orWhere('last_name', 'like', '%' . $filters['query'] . '%');
                    });
            });
        }

        if (isset($filters['is_admin'])) {
            $query->where('is_admin', $filters['is_admin']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
