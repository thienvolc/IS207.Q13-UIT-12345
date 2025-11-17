<?php

namespace App\Domains\Identity\DTOs\User\Responses;

use App\Domains\Common\DTOs\BaseDTO;

readonly class UserDTO implements BaseDTO
{
    public function __construct(
        public int $userId,
        public string $email,
        public ?string $phone,
        public bool $isAdmin,
        public int $status,
        public ?string $firstName = null,
        public ?string $middleName = null,
        public ?string $lastName = null,
        public ?string $avatar = null,
        public ?array $profile = null,
        public ?string $registeredAt = null,
        public ?string $lastLogin = null,
    ) {}

    public static function fromModel($user): self
    {
        $profile = null;
        $firstName = $middleName = $lastName = $avatar = null;
        if ($user->relationLoaded('profile') && $user->profile) {
            $p = $user->profile;
            $firstName = $p->first_name;
            $middleName = $p->middle_name;
            $lastName = $p->last_name;
            $avatar = $p->avatar;
            // keep profile raw array/object if necessary
            $profile = method_exists($p, 'toArray') ? $p->toArray() : (array)$p;
        }

        return new self(
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

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_admin' => $this->isAdmin,
            'status' => $this->status,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'avatar' => $this->avatar,
            'profile' => $this->profile,
            'registered_at' => $this->registeredAt,
            'last_login' => $this->lastLogin,
        ];
    }

    public static function collection($users): array
    {
        return $users->map(fn($u) => self::fromModel($u)->toArray())->toArray();
    }
}
