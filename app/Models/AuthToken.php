<?php

namespace App\Models;

class AuthToken
{
    public string $token;
    public int $userId;
    public bool $isAdmin;

    public function __construct(string $token, int $userId, bool $isAdmin)
    {
        $this->token = $token;
        $this->userId = $userId;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Parse token string to extract user ID and admin flag
     * Expected format: "admin-123" or "user-456"
     */
    public static function fromTokenString(string $tokenString): ?self
    {
        $parts = explode('-', $tokenString);

        if (count($parts) !== 2) {
            return null;
        }

        [$prefix, $userId] = $parts;

        if (!is_numeric($userId)) {
            return null;
        }

        if ($prefix === 'admin') {
            return new self($tokenString, (int)$userId, true);
        } elseif ($prefix === 'user') {
            return new self($tokenString, (int)$userId, false);
        }

        return null;
    }

    /**
     * Generate token string from user data
     */
    public static function generate(int $userId, bool $isAdmin): self
    {
        $prefix = $isAdmin ? 'admin' : 'user';
        $token = "{$prefix}-{$userId}";

        return new self($token, $userId, $isAdmin);
    }
}
