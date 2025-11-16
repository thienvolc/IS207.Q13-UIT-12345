<?php

namespace App\Http\Controllers\Api\User;

use App\Domains\Identity\DTOs\User\FormRequests\UpdatePasswordRequest;
use App\Domains\Identity\DTOs\User\FormRequests\UpdateProfileRequest;
use App\Domains\Identity\DTOs\User\Requests\UpdateCurrentUserDTO;
use App\Domains\Identity\DTOs\User\Requests\UpdatePasswordDTO;
use App\Domains\Identity\Services\UserService;
use App\Http\Controllers\AppController;
use App\Applications\DTOs\Responses\ResponseDTO;

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
        $user = $this->userService->getCurrentUser();
        return $this->success($user);
    }

    /**
     * PUT /me
     */
    public function update(UpdateProfileRequest $request): ResponseDTO
    {
        $dto = UpdateCurrentUserDTO::fromArray($request->validated());
        $user = $this->userService->updateCurrentUser($dto);
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
