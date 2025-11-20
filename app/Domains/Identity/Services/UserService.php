<?php

namespace App\Domains\Identity\Services;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Common\DTOs\PageResponseDTO;
use App\Domains\Identity\Constants\UserStatus;
use App\Domains\Identity\DTOs\User\Commands\AssignRolesDTO;
use App\Domains\Identity\DTOs\User\Commands\CreateUserDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdateUserProfileDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdatePasswordDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdateUserStatusDTO;
use App\Domains\Identity\DTOs\User\Queries\SearchUsersDTO;
use App\Domains\Identity\DTOs\User\Responses\CurrentUserDTO;
use App\Domains\Identity\DTOs\User\Responses\UserDTO;
use App\Domains\Identity\DTOs\User\Responses\UserEmailDTO;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\Mappers\UserMapper;
use App\Domains\Identity\Repositories\UserProfileRepository;
use App\Domains\Identity\Repositories\UserRepository;
use App\Exceptions\BusinessException;
use App\Infra\Utils\Pagination\Pageable;
use App\Infra\Utils\Pagination\Sort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(
        private UserMapper            $userMapper,
        private UserRepository        $userRepository,
        private UserProfileRepository $userProfileRepository
    ) {}

    public function getCurrent(): CurrentUserDTO
    {
        $user = $this->userRepository->getByIdOrFail($this->userId());
        return $this->userMapper->toCurrentDTO($user);
    }

    public function updateCurrent(UpdateUserProfileDTO $dto): CurrentUserDTO
    {
        $userId = $this->userId();

        return DB::transaction(function () use ($userId, $dto) {
            $user = $this->userRepository->getByIdOrFail($userId);

            $this->updateUserPhone($user, $dto->phone);
            $this->updateUserProfile($user, $dto);

            $user->load('profile');
            return $this->userMapper->toCurrentDTO($user);
        });
    }

    public function updatePassword(UpdatePasswordDTO $dto): UserEmailDTO
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userRepository->getByIdOrFail($this->userId());

            $this->assertOldPasswordMatches($user, $dto->oldPassword);
            $user->update(['password' => Hash::make($dto->newPassword)]);

            return $this->userMapper->toEmailDTO($user);
        });
    }

    /**
     * @return PageResponseDTO<UserDTO>
     */
    public function search(SearchUsersDTO $dto): PageResponseDTO
    {
        $filters = $dto->getFilters();
        $sort = Sort::of($dto->sortField, $dto->sortOrder);
        $pageable = Pageable::of($dto->page, $dto->size, $sort);

        $users = $this->userRepository->searchUsers($pageable, $filters);

        return PageResponseDTO::fromPaginator($users,
            fn($user) => $this->userMapper->toDTO($user));
    }

    public function create(CreateUserDTO $dto): UserDTO
    {
        return DB::transaction(function () use ($dto) {
            $this->assertEmailUnique($dto->email);

            $user = $this->createUser($dto);
            $this->createUserProfile($user, $dto);
            $this->assignUserRoles($user, $dto->roles);

            $user->load(['profile', 'roles']);
            return $this->userMapper->toDTO($user);
        });
    }

    public function getUserById(int $userId): UserDTO
    {
        $user = $this->userRepository->findByIdWithRolesOrFail($userId);
        return $this->userMapper->toDTO($user);
    }

    public function deleteById(int $userId): UserDTO
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findByIdWithRolesOrFail($userId);

            $this->assertNotDeletingOwnAccount($user);

            $dto = $this->userMapper->toDTO($user);
            $this->deleteUserData($user);

            return $dto;
        });
    }

    public function updateUserStatus(UpdateUserStatusDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userRepository->findByIdWithRolesOrFail($dto->userId);

            $user->update(['status' => $dto->status]);

            return $this->userMapper->toStatusDTO($user);
        });
    }

    public function assignRoles(AssignRolesDTO $dto): UserDTO
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userRepository->findByIdWithRolesOrFail($dto->userId);

            $user->roles()->sync($dto->roleIds);
            $user->load('roles');

            return $this->userMapper->toDTO($user);
        });
    }

    private function updateUserPhone(User $user, ?string $phone): void
    {
        if (!is_null($phone) && $phone !== $user->$phone) {
            $this->assertPhoneUnique($phone);
            $user->update(['phone' => $phone]);
        }
    }

    private function updateUserProfile(User $user, UpdateUserProfileDTO $dto): void
    {
        $profileData = $dto->toArray();

        if (!empty($profileData)) {
            $this->userProfileRepository->updateOrCreate($user->user_id, $profileData);
        }
    }

    private function assertOldPasswordMatches(User $user, string $oldPassword): void
    {
        if (!Hash::check($oldPassword, $user->password)) {
            throw new BusinessException(ResponseCode::OLD_PASSWORD_INCORRECT);
        }
    }

    private function assertEmailUnique(string $email): void
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new BusinessException(ResponseCode::EMAIL_CONFLICT);
        }
    }

    private function assertPhoneUnique(string $phone): void
    {
        if ($this->userRepository->existsByPhone($phone)) {
            throw new BusinessException(ResponseCode::PHONE_CONFLICT);
        }
    }

    private function createUser(CreateUserDTO $dto): User
    {
        return $this->userRepository->create([
            'email' => $dto->email,
            'phone' => $dto->phone,
            'password' => Hash::make($dto->password),
            'is_admin' => $dto->isAdmin,
            'status' => UserStatus::ACTIVE,
            'created_by' => $this->userId(),
            'updated_by' => $this->userId(),
        ]);
    }

    private function createUserProfile(User $user, CreateUserDTO $dto): void
    {
        $this->userProfileRepository->create([
            'user_id' => $user->user_id,
            'first_name' => $dto->firstName,
            'middle_name' => $dto->middleName,
            'last_name' => $dto->lastName,
            'registered_at' => now(),
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
        $user->profile()->delete();
        $user->roles()->detach();
        $user->delete();
    }

    private function userId(): int
    {
        return Auth::id();
    }
}
