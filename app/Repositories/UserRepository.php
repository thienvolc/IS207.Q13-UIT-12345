<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function findById(int $userId): ?User
    {
        return User::with(['profile', 'roles'])->find($userId);
    }

    public function findByIdWithRelations(int $userId, array $relations): ?User
    {
        return User::with($relations)->find($userId);
    }

    public function findAuthenticatedUser(int $authId): ?User
    {
        return User::with('profile')->find($authId);
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

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function updatePasswordByEmail(string $email, string $hashedPassword): int
    {
        return User::where('email', $email)->update(['password' => $hashedPassword]);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
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
