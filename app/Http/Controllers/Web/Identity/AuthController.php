<?php

namespace App\Http\Controllers\Web\Identity;

use App\Domains\Common\Constants\ResponseCode;
use App\Domains\Identity\DTOs\Auth\Commands\LoginDTO;
use App\Domains\Identity\DTOs\Auth\Commands\RegisterDTO;
use App\Domains\Identity\DTOs\Auth\Commands\SendPasswordResetDTO;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\Services\AuthService;
use App\Exceptions\BusinessException;
use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends AppController
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

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
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * POST /register
     * Sử dụng AuthService để xử lý đăng ký
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required' => 'Vui lòng nhập tên.',
        ]);

        try {
            // Sử dụng AuthService để đăng ký
            $registerDto = new RegisterDTO(
                email: $request->email,
                password: $request->password,
                phone: $request->phone,
                firstName: $request->first_name,
                middleName: $request->middle_name,
                lastName: $request->last_name,
            );

            $this->authService->register($registerDto);

            // Đăng nhập sau khi đăng ký thành công
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với PinkCapy.');
        } catch (BusinessException $e) {
            $errorMessage = match ($e->getResponseCode()) {
                ResponseCode::EMAIL_CONFLICT => 'Email đã được sử dụng.',
                ResponseCode::PHONE_CONFLICT => 'Số điện thoại đã được sử dụng.',
                default => 'Đã có lỗi xảy ra. Vui lòng thử lại.',
            };
            return back()->withInput()->withErrors(['email' => $errorMessage]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['email' => 'Đã có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    /**
     * GET /login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * POST /login
     * Sử dụng AuthService để xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        try {
            // Sử dụng AuthService để login (validate credentials)
            $loginDto = new LoginDTO(
                email: $request->email,
                password: $request->password,
            );

            $this->authService->login($loginDto);

            // Web session login
            $user = User::where('email', $request->email)->first();
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công!');
        } catch (BusinessException $e) {
            $errorMessage = match ($e->getResponseCode()) {
                ResponseCode::NOT_FOUND => 'Email không tồn tại trong hệ thống.',
                ResponseCode::INVALID_CREDENTIALS => 'Email hoặc mật khẩu không đúng.',
                ResponseCode::USER_NON_ACTIVE => 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.',
                default => 'Đã có lỗi xảy ra. Vui lòng thử lại.',
            };
            return back()->withInput()->withErrors(['email' => $errorMessage]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['email' => 'Đã có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    /**
     * POST /logout
     * Sử dụng AuthService để xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * POST /forgot-password
     * Sử dụng AuthService để gửi email reset password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        try {
            $sendPasswordResetDto = new SendPasswordResetDTO(
                email: $request->email,
            );

            $this->authService->sendPasswordResetEmail($sendPasswordResetDto);

            return back()->with('status', 'Chúng tôi đã gửi link đặt lại mật khẩu đến email của bạn.');
        } catch (BusinessException $e) {
            // Không tiết lộ email có tồn tại hay không để bảo mật
            return back()->with('status', 'Nếu email tồn tại, chúng tôi sẽ gửi link đặt lại mật khẩu.');
        } catch (\Exception $e) {
            return back()->with('status', 'Nếu email tồn tại, chúng tôi sẽ gửi link đặt lại mật khẩu.');
        }
    }

    /**
     * POST /account/password
     * Đổi mật khẩu cho user đang đăng nhập
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
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
