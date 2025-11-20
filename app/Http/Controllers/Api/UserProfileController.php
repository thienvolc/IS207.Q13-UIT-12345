<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdateUserProfileDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdatePasswordDTO;
use App\Domains\Identity\DTOs\User\FormRequests\UpdatePasswordRequest;
use App\Domains\Identity\DTOs\User\FormRequests\UpdateProfileRequest;
use App\Domains\Identity\Services\UserService;
use App\Http\Controllers\AppController;

class UserProfileController extends AppController
{
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    /**
     * GET /me
     */
    public function show(): ResponseDTO
    {
        $user = $this->userService->getCurrent();
        return $this->success($user);
    }

    /**
     * PUT /me
     */
    public function update(UpdateProfileRequest $request): ResponseDTO
    {
        $user = $this->userService->updateCurrent($request->toDTO());
        return $this->success($user);
    }

    /**
     * PUT /me/password
     */
    public function updatePassword(UpdatePasswordRequest $request): ResponseDTO
    {
        $result = $this->userService->updatePassword($request->toDTO());
        return $this->success($result);
    }
}
