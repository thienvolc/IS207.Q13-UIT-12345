<?php

namespace App\Http\Controllers\Api\Public\Identity;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Identity\DTOs\Auth\FormRequests\ForgotPasswordRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\LoginRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\RegisterRequest;
use App\Domains\Identity\DTOs\Auth\FormRequests\ResetPasswordRequest;
use App\Domains\Identity\Services\AuthService;
use App\Http\Controllers\AppController;

class AuthPublicController extends AppController
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    /**
     * POST /users/auth/register
     */
    public function register(RegisterRequest $req): ResponseDTO
    {
        $result = $this->authService->register($req->toDTO());
        return $this->success($result);
    }

    /**
     * POST /users/auth/login
     */
    public function login(LoginRequest $req): ResponseDTO
    {
        $result = $this->authService->login($req->toDTO());
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
    public function forgotPassword(ForgotPasswordRequest $req): ResponseDTO
    {
        $this->authService->sendPasswordResetEmail($req->toDTO());
        return $this->noContent();
    }

    /**
     * POST /users/auth/reset-password
     */
    public function resetPassword(ResetPasswordRequest $req): ResponseDTO
    {
        $this->authService->resetPassword($req->toDTO());
        return $this->noContent();
    }
}
