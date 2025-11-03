<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // <-- Thêm Auth
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * 1. API ĐĂNG KÝ (SIGNUP)
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:150',
            'last_name'  => 'required|string|max:150',
            'email'      => 'required|string|email|max:100|unique:users,email',
            'phone'      => 'nullable|string|max:20|unique:users,phone', // Sửa lỗi 'phone' not found
            'password'   => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Tạo User
            $user = new User();
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->is_admin = 0;
            $user->status = 1;
            $user->save(); // Sửa lỗi 'updated_at' (vì đã set $timestamps = false trong Model)

            // Tạo UserProfile
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->first_name = $request->first_name;
            $profile->last_name = $request->last_name;
            $profile->registered_at = now();
            $profile->save();

            // Tạo ApiToken
            $tokenString = Str::random(60);
            $apiToken = new ApiToken();
            $apiToken->user_id = $user->id;
            $apiToken->token = $tokenString;
            $apiToken->expires_at = now()->addDays(30);
            $apiToken->save(); // Sửa lỗi bảng 'api_tokens' not found (vì đã tạo ở Bước 1)

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully!',
                'data'    => [
                    'token' => $tokenString,
                    'user'  => $user->load('profile') // Sửa lỗi thiếu hàm 'profile()' (đã thêm ở Bước 2)
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2. API ĐĂNG NHẬP (LOGIN)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:100',
            'password' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation errors', 'errors'  => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)
                    ->where('status', 1) // Chỉ cho user 'active' đăng nhập
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'errors'  => ['email' => ['Email hoặc mật khẩu không chính xác.']]
            ], 401);
        }

        // Tạo ApiToken mới
        $tokenString = Str::random(60);
        $apiToken = new ApiToken();
        $apiToken->user_id = $user->id;
        $apiToken->token = $tokenString;
        $apiToken->expires_at = now()->addDays(30);
        $apiToken->save();

        // Cập nhật last_login
        try {
            $user->profile()->update(['last_login' => now()]);
        } catch (\Exception $e) {
            // Bỏ qua nếu lỗi
        }

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully!',
            'data'    => [
                'token' => $tokenString,
                'user'  => $user->load('profile') // Sửa lỗi thiếu hàm 'profile()'
            ]
        ], 200);
    }

    /**
     * 3. API ĐĂNG XUẤT (LOGOUT)
     */
    public function logout(Request $request)
    {
        $tokenString = $request->bearerToken();

        $apiToken = ApiToken::where('token', $tokenString)->first();

        if ($apiToken) {
            $apiToken->delete(); // Xóa token khỏi CSDL
        }

        Auth::logout(); // Đăng xuất khỏi session (nếu có)

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully.'
        ], 200);
    }
}