<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Domains\Identity\DTOs\Auth\Commands\ResetPasswordDTO;
use App\Domains\Identity\DTOs\Auth\Commands\SendPasswordResetDTO;
use App\Domains\Identity\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Show admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Send password reset email.
     */
    public function sendResetEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $dto = new SendPasswordResetDTO(email: $validated['email']);
            $this->authService->sendPasswordResetEmail($dto);

            return back()->with('status', 'Đã gửi link đặt lại mật khẩu đến email của bạn!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }
    }

    /**
     * Show reset password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        return view('admin.auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $dto = new ResetPasswordDTO(
                email: $validated['email'],
                passwordResetToken: $validated['token'],
                newPassword: $validated['password']
            );
            $this->authService->resetPassword($dto);

            return redirect()->route('admin.login')
                ->with('status', 'Mật khẩu đã được đặt lại thành công! Vui lòng đăng nhập.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn.']);
        }
    }
}
