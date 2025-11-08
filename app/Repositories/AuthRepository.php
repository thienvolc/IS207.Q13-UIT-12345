<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AuthRepository
{
    public function findPasswordResetByEmail(string $email): ?object
    {
        return DB::table('password_resets')
            ->where('email', $email)
            ->first();
    }

    public function createOrUpdatePasswordReset(string $email, string $hashedToken): void
    {
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $hashedToken,
                'created_at' => now(),
            ]
        );
    }

    public function deletePasswordResetByEmail(string $email): void
    {
        DB::table('password_resets')->where('email', $email)->delete();
    }
}
