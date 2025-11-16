<?php

namespace App\Http\Controllers\Api\Auth;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Identity\DTOs\Auth\FormRequests\ForgotPasswordRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\LoginRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\RegisterRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\ResetPasswordRequest;
use App\Domains\Identity\DTOs\Auth\Requests\LoginDTO;
use App\Domains\Identity\DTOs\Auth\Requests\RegisterDTO;
use App\Domains\Identity\Services\AuthService;
use App\Http\Controllers\AppController;

class AuthController extends AppController
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    /**
     * POST /users/auth/register
     */
    public function register(RegisterRequest $request): ResponseDTO
    {
        $dto = RegisterDTO::fromArray($request->validated());
        $result = $this->authService->register($dto);
        return $this->success($result);
    }

    /**
     * POST /users/auth/login
     */
    public function login(LoginRequest $request): ResponseDTO
    {
        $dto = LoginDTO::fromArray($request->validated());
        $result = $this->authService->login($dto);
        return $this->success($result);
    }

    /**
     * POST /users/auth/logout
     */
    public function logout(): ResponseDTO
    {
        $this->authService->logout();
        return $this->noContent();
    }

    /**
     * POST /users/auth/forgot-password
     */
    public function forgotPassword(ForgotPasswordRequest $request): ResponseDTO
    {
        $dto = \App\Domains\Identity\DTOs\Auth\Requests\SendPasswordResetDTO::fromArray($request->validated());
        $this->authService->sendPasswordResetEmail($dto);
        return $this->noContent();
    }

    /**
     * POST /users/auth/reset-password
     */
    public function resetPassword(ResetPasswordRequest $request): ResponseDTO
    {
        $dto = \App\Domains\Identity\DTOs\Auth\Requests\ResetPasswordDTO::fromArray($request->validated());
        $this->authService->resetPassword($dto);
        return $this->noContent();
    }
}
