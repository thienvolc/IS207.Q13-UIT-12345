<?php

namespace App\Http\Middleware;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Identity\Constants\UserStatus;
use App\Domains\Identity\Entities\User;
use App\Exceptions\BusinessException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): mixed
    {
        $token = $this->getBearerToken($request);
        [$tokenRole, $userId] = $this->parseToken($token);

        $user = $this->findUserOrFail($userId);
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

        return [$matches[1], (int)$matches[2]];
    }

    private function findUserOrFail(int $userId): User
    {
        return User::find($userId)
            ?? throw new BusinessException(ResponseCode::NOT_FOUND);
    }

    private function assertUserIsActive(User $user): void
    {
        if ($user->status !== UserStatus::ACTIVE) {
            throw new BusinessException(ResponseCode::USER_NON_ACTIVE);
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
