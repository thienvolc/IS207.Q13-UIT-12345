<?php

namespace App\Http\Controllers\Api\User;

use App\Domains\Identity\DTOs\User\FormRequests\AssignRolesRequest;
use App\Domains\Identity\DTOs\User\FormRequests\CreateUserRequest;
use App\Domains\Identity\DTOs\User\FormRequests\SearchUsersRequest;
use App\Domains\Identity\DTOs\User\FormRequests\UpdateUserStatusRequest;
use App\Domains\Identity\DTOs\User\Requests\AssignRolesDTO;
use App\Domains\Identity\DTOs\User\Requests\CreateUserDTO;
use App\Domains\Identity\DTOs\User\Requests\SearchUsersDTO;
use App\Domains\Identity\DTOs\User\Requests\UpdateUserStatusDTO;
use App\Domains\Identity\Services\UserService;
use App\Http\Controllers\AppController;
use App\Applications\DTOs\Responses\ResponseDTO;

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
    public function index(SearchUsersRequest $request): ResponseDTO
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchUsersDTO::fromArray([
            'query' => $request->input('query'),
            'is_admin' => $request->input('is_admin'),
            'status' => $request->input('status'),
            'page' => $request->getPage(),
            'size' => $request->getSize(),
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
        ]);

        $users = $this->userService->searchUsers($dto);

        return $this->success($users);
    }

    /**
     * POST /admin/users
     */
    public function store(CreateUserRequest $request): ResponseDTO
    {
        $dto = CreateUserDTO::fromArray($request->validated());
        $user = $this->userService->createUser($dto);
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
        $user = $this->userService->deleteUser($user_id);
        return $this->success($user);
    }

    /**
     * PATCH /admin/users/{user_id}/status
     */
    public function updateStatus(UpdateUserStatusRequest $request, int $user_id): ResponseDTO
    {
        $dto = UpdateUserStatusDTO::fromArray($request->validated(), $user_id);
        $result = $this->userService->updateUserStatus($dto);
        return $this->success($result);
    }

    /**
     * PATCH /admin/users/{user_id}/roles
     */
    public function updateRoles(AssignRolesRequest $request, int $user_id): ResponseDTO
    {
        $dto = AssignRolesDTO::fromArray($request->validated(), $user_id);
        $roles = $this->userService->assignRoles($dto);
        return $this->success($roles);
    }
}
