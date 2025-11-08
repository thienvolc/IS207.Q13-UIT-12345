<?php

namespace App\Http\Middleware;

use App\Constants\UserStatus;
use App\Exceptions\BusinessException;
use App\Constants\ResponseCode;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): mixed
    {
        $token = $this->getBearerToken($request);
        [$tokenRole, $userId] = $this->parseToken($token);

        $user = $this->findUser($userId);
        $this->assertUserIsActive($user);
        $this->assertRoleAllowed($role, $tokenRole);

        Auth::loginUsingId($userId);
        $request->attributes->set('auth_user_id', $userId);
        $request->attributes->set('is_admin', $tokenRole === 'admin');
        $request->attributes->set('user', $user);

        return $next($request);
    }

    private function getBearerToken(Request $request): string
    {
        $header = $request->header('Authorization');

        if (empty($header)) {
            throw new BusinessException(ResponseCode::UNAUTHORIZED);
        }

        $prefix = 'Bearer ';
        if (!str_starts_with($header, $prefix)) {
            throw new BusinessException(ResponseCode::INVALID_AUTHORIZATION_FORMAT);
        }

        return trim(substr($header, strlen($prefix)));
    }

    private function parseToken(string $token): array
    {
        $matches = [];
        if (!preg_match('/^(admin|user)-(\d+)$/', $token, $matches)) {
            throw new BusinessException(ResponseCode::INVALID_TOKEN_FORMAT);
        }

        // $matches[2] = [<role>, <id>]
        return [$matches[1], (int)$matches[2]];
    }

    private function findUser(int $userId): User
    {
        $user = User::find($userId);

        if (!$user) {
            throw new BusinessException(ResponseCode::NOT_FOUND);
        }

        return $user;
    }

    private function assertUserIsActive(User $user): void
    {
        if ($user->status !== UserStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::USER_INACTIVE);
        }
    }

    private function assertRoleAllowed(?string $requiredRole, string $tokenRole): void
    {
        if ($requiredRole === null) {
            return;
        }

        if ($requiredRole === 'admin' && $tokenRole !== 'admin') {
            throw new BusinessException(ResponseCode::FORBIDDEN);
        }
    }
}
