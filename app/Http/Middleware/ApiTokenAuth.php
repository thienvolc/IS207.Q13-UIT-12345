<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     * Accepts optional role parameter 'admin' to require admin token
     */
    public function handle(Request $request,
                           Closure $next,
                           ?string $role = null): mixed {
        $header = $request->header('Authorization');
        if (! $header) {
            return response()->json([
                'meta' => [
                    'code' => '401001',
                    'type' => 'ERROR',
                    'message' => 'Missing Authorization',
                    'extra_meta' => (object)[]
                ],
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'meta' => [
                    'code' => '401002',
                    'type' => 'ERROR',
                    'message' => 'Invalid Authorization format. Use: Bearer <token>',
                    'extra_meta' => (object)[]
                ],
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }

        $tokenValue = trim(substr($header, 7));
        $token = ApiToken::where('token', $tokenValue)->first();

        if (! $token) {
            return response()->json([
                'meta' => [
                    'code' => '401003',
                    'type' => 'ERROR',
                    'message' => 'Invalid token',
                    'extra_meta' => (object)[]
                ],
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($role === 'admin' && ! $token->is_admin) {
            return response()->json([
                'meta' => [
                    'code' => '403001',
                    'type' => 'ERROR',
                    'message' => 'Admin privileges required',
                    'extra_meta' => (object)[]
                ],
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        // Set the authenticated user for convenience
        $user = $token->user;
        if ($user) {
            Auth::loginUsingId($user->id);
            // also attach to request
            $request->attributes->set('auth_user_id', $user->id);
            $request->attributes->set('is_admin', (bool) $token->is_admin);
            $request->attributes->set('api_token', $token);
        }

        return $next($request);
    }
}

