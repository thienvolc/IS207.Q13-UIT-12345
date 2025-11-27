<?php

namespace App\Http\Controllers\Web\Identity;

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends AppController
{
    /**
     * GET /forgot-password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    /**
     * GET /register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    /**
     * GET /login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * POST /account/password
     * Đổi mật khẩu cho user đang đăng nhập
     */
    public function updatePassword(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Đổi mật khẩu thành công!');
    }
}
