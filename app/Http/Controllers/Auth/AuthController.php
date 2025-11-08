<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\Auth\LoginDto;
use App\Dtos\Auth\RegisterDto;
use App\Dtos\Auth\ResetPasswordDto;
use App\Dtos\Auth\SendPasswordResetDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends AppController
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * POST /users/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterDto::fromArray($request->validated());
        $result = $this->authService->register($dto);
        return $this->success($result);
    }

    /**
     * POST /users/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginDto::fromArray($request->validated());
        $result = $this->authService->login($dto);
        return $this->success($result);
    }

    /**
     * POST /users/auth/logout
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->noContent();
    }

    /**
     * POST /users/auth/forgot-password
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $dto = SendPasswordResetDto::fromArray($request->validated());
        $this->authService->sendPasswordResetEmail($dto);
        return $this->noContent();
    }

    /**
     * POST /users/auth/reset-password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $dto = ResetPasswordDto::fromArray($request->validated());
        $this->authService->resetPassword($dto);
        return $this->noContent();
    }
}
