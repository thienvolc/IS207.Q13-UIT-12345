<?php

namespace App\Mappers;

use App\Models\User;

class AuthMapper
{
    public function toUserDto(User $user): array
    {
        return [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'registered_at' => $user->registered_at?->toIso8601String(),
        ];
    }

    public function toLoginResponse(User $user, string $tokenType, string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => $tokenType,
            'user' => $this->buildLoginPayload($user),
        ];
    }

    public function buildLoginPayload(User $user): array
    {
        return [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'first_name' => $user->profile->first_name ?? null,
            'last_name' => $user->profile->last_name ?? null,
            'avatar' => $user->profile->avatar ?? null,
        ];
    }
}
