<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Constants\ResponseCode;
use App\Constants\UserStatus;
use App\Utils\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): mixed
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return ResponseFactory::error(ResponseCode::UNAUTHORIZED);
        }

        if (!str_starts_with($header, 'Bearer ')) {
            $customCode = array_merge(
                ResponseCode::UNAUTHORIZED,
                ['message' => 'Invalid Authorization format. Use: Bearer <token>']
            );
            return ResponseFactory::error($customCode);
        }

        $token = trim(substr($header, 7));

        if (!preg_match('/^(admin|user)-(\d+)$/', $token, $matches)) {
            $customCode = array_merge(
                ResponseCode::UNAUTHORIZED,
                ['message' => 'Invalid token format. Use: admin-{id} or user-{id}']
            );
            return ResponseFactory::error($customCode);
        }

        $tokenRole = $matches[1];
        $userId = (int)$matches[2];

        $user = User::find($userId);
        if (!$user) {
            $customCode = array_merge(
                ResponseCode::UNAUTHORIZED,
                ['message' => 'User not found']
            );
            return ResponseFactory::error($customCode);
        }

        if ($user->status !== UserStatus::ACTIVE) {
            $customCode = array_merge(
                ResponseCode::UNAUTHORIZED,
                ['message' => 'User is inactive']
            );
            return ResponseFactory::error($customCode);
        }

        $isAdmin = ($tokenRole === 'admin');

        if ($role === 'admin' && !$isAdmin) {
            return ResponseFactory::error(ResponseCode::FORBIDDEN);
        }

        Auth::loginUsingId($userId);
        $request->attributes->set('auth_user_id', $userId);
        $request->attributes->set('is_admin', $isAdmin);
        $request->attributes->set('user', $user);

        return $next($request);
    }
}
