<?php

namespace App\Http\Controllers\Api\Me;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Dùng để lấy user đã đăng nhập
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate_Validation_Rule;
use Illuminate\Validation\Rule; // Dùng cho validation 'unique' (quan trọng)

class ProfileController extends Controller
{
    /**
     * 1. API XEM PROFILE (View Profile)
     * (GET /me/profile)
     */
    public function show(Request $request)
    {
        // Middleware 'auth.api' đã xác thực và $request->user() sẽ trả về
        // bản ghi 'User' của người dùng hiện tại.
        $user = $request->user();

        // Load thông tin profile (từ bảng user_profiles)
        $user->load('profile'); 

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data'    => $user
        ], 200);
    }

    /**
     * 2. API CẬP NHẬT PROFILE (Update Profile)
     * (PUT /me/profile)
     */
    public function update(Request $request)
    {
        // Lấy user đã đăng nhập
        $user = $request->user();

        // === VALIDATE DỮ LIỆU ===
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:150',
            'last_name'  => 'sometimes|required|string|max:150',
            'phone'      => [
                'sometimes',
                'required',
                'string',
                'max:20',
                // Rule 'unique': Kiểm tra SĐT là duy nhất trong bảng 'users',
                // NHƯNG bỏ qua (ignore) ID của user hiện tại.
                Rule::unique('users')->ignore($user->id), 
            ],
            // Thêm các trường khác bạn muốn cho phép cập nhật ở đây
            // ví dụ: 'avatar', 'middle_name'...
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        // === BẮT ĐẦU TRANSACTION ===
        // Vì chúng ta cập nhật 2 bảng (users và user_profiles)
        DB::beginTransaction();
        try {
            
            // 1. Cập nhật bảng 'users' (nếu có phone)
            if ($request->has('phone')) {
                $user->phone = $request->phone;
                $user->save(); // Sẽ chỉ lưu cột 'phone' nếu nó thay đổi
            }

            // 2. Cập nhật bảng 'user_profiles'
            // Lấy các trường mà profile có
            $profileData = $request->only(['first_name', 'last_name', 'middle_name', 'avatar']);

            if (!empty($profileData)) {
                // Dùng 'update' thay vì 'save' cho quan hệ hasOne
                $user->profile()->update($profileData);
            }
            
            // Mọi thứ thành công
            DB::commit();

            // === TRẢ VỀ DỮ LIỆU MỚI ===
            // Tải lại (refresh) quan hệ profile để lấy dữ liệu mới nhất
            $user->load('profile');

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data'    => $user
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}