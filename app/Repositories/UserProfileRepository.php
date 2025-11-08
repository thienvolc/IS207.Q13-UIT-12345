<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserProfile;

class UserProfileRepository
{
    public function updateOrCreate(int $userId, array $data): UserProfile
    {
        return UserProfile::updateOrCreate(['user_id' => $userId], $data);
    }

    public function create(array $data): UserProfile
    {
        return UserProfile::create($data);
    }

    public function delete(User $user): bool
    {
        return $user->profile()->delete();
    }
}
