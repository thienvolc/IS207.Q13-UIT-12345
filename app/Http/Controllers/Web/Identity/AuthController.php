<?php

namespace App\Http\Controllers\Web\Identity;

use App\Http\Controllers\AppController;

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
}
