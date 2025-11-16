<?php

namespace App\Domains\Identity\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Identity\Constants\UserStatus;
use App\Domains\Identity\DTOs\Auth\Responses\UserAdminDTO;
use App\Domains\Identity\DTOs\User\Requests\AssignRolesDTO;
use App\Domains\Identity\DTOs\User\Requests\CreateUserDTO;
use App\Domains\Identity\DTOs\User\Requests\SearchUsersDTO;
use App\Domains\Identity\DTOs\User\Requests\UpdateCurrentUserDTO;
use App\Domains\Identity\DTOs\User\Requests\UpdatePasswordDTO;
use App\Domains\Identity\DTOs\User\Requests\UpdateUserStatusDTO;
use App\Domains\Identity\DTOs\User\Responses\UserDTO;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\Repositories\UserProfileRepository;
use App\Domains\Identity\Repositories\UserRepository;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\PaginationUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(
        private UserRepository        $userRepository,
        private UserProfileRepository $userProfileRepository
    )
    {
    }

    public function getCurrentUser(): array
    {
        $user = $this->findAuthenticatedUser();
        return UserDTO::transform($user);
    }

    public function updateCurrentUser(UpdateCurrentUserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->findAuthenticatedUser();

            $this->updateUserProfile($user, $dto);
            $this->updateUserPhone($user, $dto);

            $user->load('profile');
            return UserDTO::transform($user);
        });
    }

    public function updatePassword(UpdatePasswordDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->findAuthenticatedUser();
            $this->assertOldPasswordMatches($user, $dto->oldPassword);

            $this->userRepository->update($user, ['password' => Hash::make($dto->newPassword)]);

            return [
                'user_id' => $user->user_id,
                'email' => $user->email,
            ];
        });
    }

    public function searchUsers(SearchUsersDTO $dto): array
    {
        $filters = $dto->getFilters();
        $offset = ($dto->page - 1) * $dto->size;

        $totalCount = $this->userRepository->countWithFilters($filters);
        $users = $this->userRepository->searchWithFilters($filters, $dto->sortField, $dto->sortOrder, $offset, $dto->size);

        return PaginationUtil::fromPageSize(
            UserAdminDTO::collection($users),
            $dto->page,
            $dto->size,
            $totalCount
        );
    }

    public function createUser(CreateUserDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $this->ensureEmailUnique($dto->email);

            $user = $this->createUserRecord($dto);
            $this->createUserProfile($user, $dto);
            $this->assignUserRoles($user, $dto->roles);

            $user->load(['profile', 'roles']);
            return UserAdminDTO::transform($user);
        });
    }

    public function getUserById(int $userId): array
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return UserAdminDTO::transform($user);
    }

    public function deleteUser(int $userId): array
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $this->assertNotDeletingOwnAccount($user);

            $deletedUser = $user->replicate();
            $deletedUser->load(['profile', 'roles']);

            $this->deleteUserData($user);

            return UserAdminDTO::transform($deletedUser);
        });
    }

    public function updateUserStatus(UpdateUserStatusDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userRepository->findById($dto->userId);

            if (!$user) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $this->userRepository->update($user, ['status' => $dto->status]);

            return [
                'user_id' => $user->user_id,
                'status' => $user->status,
            ];
        });
    }

    public function assignRoles(AssignRolesDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userRepository->findById($dto->userId);

            if (!$user) {
                throw new BusinessException(ResponseCode::NOT_FOUND);
            }

            $roleIds = array_column($dto->roles, 'role_id');
            $user->roles()->sync($roleIds);

            $user->load('roles');

            return $user->roles->map(function ($role) {
                return [
                    'role_id' => $role->role_id,
                    'name' => $role->name,
                ];
            })->toArray();
        });
    }

    private function findAuthenticatedUser(): User
    {
        $user = $this->userRepository->findAuthenticatedUser(Auth::id());

        if (!$user) {
            throw new BusinessException(ResponseCode::UNAUTHORIZED);
        }

        return $user;
    }

    private function updateUserProfile(User $user, UpdateCurrentUserDTO $dto): void
    {
        $profileData = array_filter([
            'first_name' => $dto->firstName,
            'middle_name' => $dto->middleName,
            'last_name' => $dto->lastName,
        ], fn($value) => !is_null($value));

        if (!empty($profileData)) {
            $this->userProfileRepository->updateOrCreate($user->user_id, $profileData);
        }
    }

    private function updateUserPhone(User $user, UpdateCurrentUserDTO $dto): void
    {
        if (!is_null($dto->phone) && $dto->phone !== $user->phone) {
            $this->ensurePhoneUnique($dto->phone);
            $this->userRepository->update($user, ['phone' => $dto->phone]);
        }
    }

    private function assertOldPasswordMatches(User $user, string $oldPassword): void
    {
        if (!Hash::check($oldPassword, $user->password)) {
            throw new BusinessException(ResponseCode::OLD_PASSWORD_INCORRECT);
        }
    }

    private function ensureEmailUnique(string $email): void
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new BusinessException(ResponseCode::EMAIL_CONFLICT);
        }
    }

    private function ensurePhoneUnique(string $phone): void
    {
        if ($this->userRepository->existsByPhone($phone)) {
            throw new BusinessException(ResponseCode::PHONE_CONFLICT);
        }
    }

    private function createUserRecord(CreateUserDTO $dto): User
    {
        return $this->userRepository->create([
            'email' => $dto->email,
            'phone' => $dto->phone,
            'password' => Hash::make($dto->password),
            'is_admin' => $dto->isAdmin,
            'status' => UserStatus::ACTIVE,
            'registered_at' => now(),
        ]);
    }

    private function createUserProfile(User $user, CreateUserDTO $dto): void
    {
        $this->userProfileRepository->create([
            'user_id' => $user->user_id,
            'first_name' => $dto->firstName,
            'middle_name' => $dto->middleName,
            'last_name' => $dto->lastName,
        ]);
    }

    private function assignUserRoles(User $user, array $roles): void
    {
        if (!empty($roles)) {
            $roleIds = array_column($roles, 'role_id');
            $user->roles()->sync($roleIds);
        }
    }

    private function assertNotDeletingOwnAccount(User $user): void
    {
        if ($user->user_id === Auth::id()) {
            throw new BusinessException(ResponseCode::CANNOT_DELETE_OWN_ACCOUNT);
        }
    }

    private function deleteUserData(User $user): void
    {
        $this->userProfileRepository->delete($user);
        $user->roles()->detach();
        $this->userRepository->delete($user);
    }
}
