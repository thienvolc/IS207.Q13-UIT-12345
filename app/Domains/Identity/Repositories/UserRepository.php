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

    private function applyFilters($query, UserFilter $f): void
    {
        $query->when($f->query,
            fn($q, $v) =>
            $q->where('email', 'like', '%' . $v . '%')
            ->orWhere('phone', 'like', '%' . $v . '%')
            ->orWhereHas('profile',
                fn($subQ) =>
                $subQ->where('first_name', 'like', '%' . $v . '%')
                ->orWhere('last_name', 'like', '%' . $v . '%'))
        );
        $query->when($f->isAdmin, fn($q, $v) => $q->where('is_admin', $v));
        $query->when($f->status, fn($q, $v) => $q->where('status', $v));
    }
}
