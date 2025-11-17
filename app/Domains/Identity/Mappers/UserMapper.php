<?php

namespace App\Domains\Identity\Mappers;

use App\Domains\Identity\DTOs\User\Responses\CurrentUserDTO;
use App\Domains\Identity\DTOs\User\Responses\RoleDTO;
use App\Domains\Identity\DTOs\User\Responses\UserDTO;
use App\Domains\Identity\DTOs\User\Responses\UserEmailDTO;
use App\Domains\Identity\DTOs\User\Responses\UserStatusDTO;
use App\Domains\Identity\Entities\Role;
use App\Domains\Identity\Entities\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

readonly class UserMapper
{
    public function toCurrentDTO(User $user): CurrentUserDTO
    {
        $profile = null;
        $firstName = $middleName = $lastName = $avatar = null;

        if ($user->relationLoaded('profile') && $user->profile) {
            $p = $user->profile;
            $firstName = $p->first_name;
            $middleName = $p->middle_name;
            $lastName = $p->last_name;
            $avatar = $p->avatar;
            $profile = method_exists($p, 'toArray') ? $p->toArray() : (array)$p;
        }

        return new CurrentUserDTO(
            userId: $user->user_id,
            email: $user->email,
            phone: $user->phone,
            isAdmin: (bool)$user->is_admin,
            status: $user->status,
            firstName: $firstName,
            middleName: $middleName,
            lastName: $lastName,
            avatar: $avatar,
            profile: $profile,
            registeredAt: optional($user->registered_at)?->toIso8601String(),
            lastLogin: optional($user->last_login)?->toIso8601String(),
        );
    }

    /**
     * @param EloquentCollection<int, User> $users
     * @return UserDTO[]
     */
    public function toDTOs(EloquentCollection $users): array
    {
        return $users->map(fn($user) => $this->toDTO($user))->all();
    }

    public function toDTO(User $user): UserDTO
    {
        $roles = $this->toRoleDTOs($user->roles);

        return new UserDTO(
            userId: $user->user_id,
            email: $user->email,
            phone: $user->phone,
            isAdmin: $user->is_admin,
            status: $user->status,
            firstName: $user->profile->first_name,
            middleName: $user->profile->middle_name,
            lastName: $user->profile->last_name,
            avatar: $user->profile->avatar,
            profile: $user->profile->profile,
            registeredAt: $user->registered_at?->toIso8601String(),
            lastLogin: $user->last_login?->toIso8601String(),
            createdAt: $user->created_at?->toIso8601String(),
            updatedAt: $user->updated_at?->toIso8601String(),
            createdBy: $user->created_by,
            updatedBy: $user->updated_by,
            roles: $roles->toArray(),
        );
    }

    public function toEmailDTO(User $user): UserEmailDTO
    {
        return new UserEmailDTO(
            userId: $user->user_id,
            email: $user->email,
        );
    }

    public function toStatusDTO(User $user): UserStatusDTO
    {
        return new UserStatusDTO(
            userId: $user->user_id,
            status: $user->status,
        );
    }

    /**
     * @param EloquentCollection<int, Role> $roles
     * @return Collection<int, RoleDTO>
     */
    private function toRoleDTOs(EloquentCollection $roles): Collection
    {
        return $roles->map(fn($r) => $this->toRoleDTO($r));
    }

    private function toRoleDTO(Role $role): RoleDTO
    {
        return new RoleDTO(
            roleId: $role->role_id,
            name: $role->name,
        );
    }
}
