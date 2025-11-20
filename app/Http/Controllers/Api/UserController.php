<?php

namespace App\Http\Controllers\Api;

use App\Applications\DTOs\Responses\ResponseDTO;
use App\Domains\Identity\DTOs\User\Commands\AssignRolesDTO;
use App\Domains\Identity\DTOs\User\Commands\CreateUserDTO;
use App\Domains\Identity\DTOs\User\Commands\UpdateUserStatusDTO;
use App\Domains\Identity\DTOs\User\FormRequests\AssignRolesRequest;
use App\Domains\Identity\DTOs\User\FormRequests\CreateUserRequest;
use App\Domains\Identity\DTOs\User\FormRequests\SearchUsersRequest;
use App\Domains\Identity\DTOs\User\FormRequests\UpdateUserStatusRequest;
use App\Domains\Identity\DTOs\User\Queries\SearchUsersDTO;
use App\Domains\Identity\Services\UserService;
use App\Http\Controllers\AppController;

class UserController extends AppController
{
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    /**
     * GET /admin/users
     */
    public function index(SearchUsersRequest $req): ResponseDTO
    {
        $users = $this->userService->search($req->toDTO());
        return $this->success($users);
    }

    /**
     * POST /admin/users
     */
    public function store(CreateUserRequest $req): ResponseDTO
    {
        $user = $this->userService->create($req->toDTO());
        return $this->created($user);
    }

    /**
     * GET /admin/users/{user_id}
     */
    public function show(int $user_id): ResponseDTO
    {
        $user = $this->userService->getUserById($user_id);
        return $this->success($user);
    }

    /**
     * DELETE /admin/users/{user_id}
     */
    public function destroy(int $user_id): ResponseDTO
    {
        $user = $this->userService->deleteById($user_id);
        return $this->success($user);
    }

    /**
     * PATCH /admin/users/{user_id}/status
     */
    public function updateStatus(UpdateUserStatusRequest $req, int $user_id): ResponseDTO
    {
        $result = $this->userService->updateUserStatus($req->toDTO($user_id));
        return $this->success($result);
    }

    /**
     * PATCH /admin/users/{user_id}/roles
     */
    public function updateRoles(AssignRolesRequest $req, int $user_id): ResponseDTO
    {
        $roles = $this->userService->assignRoles($req->toDTO($user_id));
        return $this->success($roles);
    }
}
