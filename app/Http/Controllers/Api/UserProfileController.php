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
        $dto = UpdateUserProfileDTO::fromArray($request->validated());
        $user = $this->userService->updateCurrent($dto);
        return $this->success($user);
    }

    /**
     * PUT /me/password
     */
    public function updatePassword(UpdatePasswordRequest $request): ResponseDTO
    {
        $dto = UpdatePasswordDTO::fromArray($request->validated());
        $result = $this->userService->updatePassword($dto);
        return $this->success($result);
    }
}
