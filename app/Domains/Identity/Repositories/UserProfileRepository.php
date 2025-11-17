<?php

namespace App\Domains\Identity\Repositories;

use App\Domains\Identity\Entities\User;
use App\Domains\Identity\Entities\UserProfile;

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
}
