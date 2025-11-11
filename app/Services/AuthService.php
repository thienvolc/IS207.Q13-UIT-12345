<?php

namespace App\Services;

use App\Constants\ResponseCode;
use App\Constants\UserStatus;
use App\Dtos\Auth\LoginDto;
use App\Dtos\Auth\RegisterDto;
use App\Dtos\Auth\ResetPasswordDto;
use App\Dtos\Auth\SendPasswordResetDto;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Models\UserProfile;
use App\Mappers\AuthMapper;
use App\Repositories\UserRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private AuthMapper $mapper,
        private UserRepository $userRepository,
        private UserProfileRepository $userProfileRepository,
        private AuthRepository $authRepository
    ) {
    }

    public function register(RegisterDto $registerDto): array
    {
        return DB::transaction(function () use ($registerDto) {
            $this->ensureEmailUnique($registerDto->email);

            if (!empty($registerDto->phone)) {
                $this->ensurePhoneUnique($registerDto->phone);
            }

            $user = $this->createUser($registerDto);
            $this->createProfile($user, $registerDto);

            return $this->mapper->toUserDto($user);
        });
    }

    public function login(LoginDto $dto): array
    {
        $user = $this->findUserByEmail($dto->email);
        $this->assertPasswordMatches($user, $dto->password);
        $this->assertUserIsActive($user);

        $token = $this->generateTokenForUser($user);
        $user->update(['last_login' => now()]);

        return $this->mapper->toLoginResponse($user, 'Bearer', $token);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function sendPasswordResetEmail(SendPasswordResetDto $dto): void
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user) {
            return;
        }

        $token = Str::random(64);

        $this->authRepository->createOrUpdatePasswordReset(
            $dto->email,
            Hash::make($token)
        );

        // TODO: send email with token
    }

    public function resetPassword(ResetPasswordDto $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $this->validatePasswordResetToken($dto);

            $this->userRepository->updatePasswordByEmail(
                $dto->email,
                Hash::make($dto->newPassword)
            );

            $this->authRepository->deletePasswordResetByEmail($dto->email);
        });
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

    private function createUser(RegisterDto $dto): User
    {
        return $this->userRepository->create([
            'email' => $dto->email,
            'phone' => $dto->phone,
            'password' => Hash::make($dto->password),
            'is_admin' => false,
            'status' => UserStatus::ACTIVE,
            'registered_at' => now(),
        ]);
    }

    private function createProfile(User $user, RegisterDto $dto): void
    {
        $this->userProfileRepository->create([
            'user_id' => $user->user_id,
            'first_name' => $dto->firstName,
            'middle_name' => $dto->middleName,
            'last_name' => $dto->lastName,
        ]);
    }


    private function findUserByEmail(string $email): User
    {
        $user = $this->userRepository->findByEmailWithProfile($email);
        if (!$user) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $user;
    }

    private function assertPasswordMatches(User $user, string $password): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new BusinessException(ResponseCode::INVALID_CREDENTIALS);
        }
    }

    private function assertUserIsActive(User $user): void
    {
        if ($user->status !== UserStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::USER_INACTIVE);
        }
    }

    private function generateTokenForUser(User $user): string
    {
        $tokenPrefix = $user->is_admin ? 'admin' : 'user';
        return "{$tokenPrefix}-{$user->user_id}";
    }

    private function validatePasswordResetToken(ResetPasswordDto $dto): void
    {
        $resetRecord = $this->authRepository->findPasswordResetByEmail($dto->email);

        if (!$resetRecord) {
            throw new BusinessException(ResponseCode::PASSWORD_RESET_TOKEN_INVALID);
        }

        if (!Hash::check($dto->passwordResetToken, $resetRecord->token)) {
            throw new BusinessException(ResponseCode::PASSWORD_RESET_TOKEN_INVALID);
        }

        if (now()->diffInHours($resetRecord->created_at) > 24) {
            throw new BusinessException(ResponseCode::PASSWORD_RESET_TOKEN_EXPIRED);
        }
    }
}
