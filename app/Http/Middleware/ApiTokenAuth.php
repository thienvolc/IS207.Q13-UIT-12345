<?php

namespace App\Http\Middleware;

use App\Models\ApiToken; // <-- (Phải có dòng này)
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <-- 1. THÊM DÒNG NÀY

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->get('api_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'API token not provided'
            ], 401);
        }

        $apiToken = ApiToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        // Thêm kiểm tra !$apiToken->user để đảm bảo user tồn tại
        if (!$apiToken || !$apiToken->user) { 
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired API token'
            ], 401);
        }

        // === 2. SỬA DÒNG NÀY ===
        // Thay vì $request->merge, hãy dùng Auth::login()
        // Dòng này sẽ đăng nhập user, và làm cho $request->user() hoạt động
        Auth::login($apiToken->user);
        
        return $next($request);
    }
}