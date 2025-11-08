<?php

namespace App\Http\Controllers\Me;

use App\Dtos\User\UpdateCurrentUserDto;
use App\Dtos\User\UpdatePasswordDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends AppController
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * GET /me
     */
    public function show(): JsonResponse
    {
        $user = $this->userService->getCurrentUser();
        return $this->success($user);
    }

    /**
     * PUT /me
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $dto = UpdateCurrentUserDto::fromArray($request->validated());
        $user = $this->userService->updateCurrentUser($dto);
        return $this->success($user);
    }

    /**
     * PUT /me/password
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $dto = UpdatePasswordDto::fromArray($request->validated());
        $result = $this->userService->updatePassword($dto);
        return $this->success($result);
    }
}
