<?php

namespace App\Http\Controllers\Admin;

use App\Dtos\User\AssignRolesDto;
use App\Dtos\User\CreateUserDto;
use App\Dtos\User\SearchUsersDto;
use App\Dtos\User\UpdateUserStatusDto;
use App\Http\Controllers\AppController;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\SearchUsersRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Requests\User\AssignRolesRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends AppController
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * GET /admin/users
     */
    public function index(SearchUsersRequest $request): JsonResponse
    {
        [$sortField, $sortOrder] = $request->getSort();

        $dto = SearchUsersDto::fromArray([
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
    public function store(CreateUserRequest $request): JsonResponse
    {
        $dto = CreateUserDto::fromArray($request->validated());
        $user = $this->userService->createUser($dto);
        return $this->created($user);
    }

    /**
     * GET /admin/users/{user_id}
     */
    public function show(int $user_id): JsonResponse
    {
        $user = $this->userService->getUserById($user_id);
        return $this->success($user);
    }

    /**
     * DELETE /admin/users/{user_id}
     */
    public function destroy(int $user_id): JsonResponse
    {
        $user = $this->userService->deleteUser($user_id);
        return $this->success($user);
    }

    /**
     * PATCH /admin/users/{user_id}/status
     */
    public function updateStatus(UpdateUserStatusRequest $request, int $user_id): JsonResponse
    {
        $dto = UpdateUserStatusDto::fromArray($request->validated(), $user_id);
        $result = $this->userService->updateUserStatus($dto);
        return $this->success($result);
    }

    /**
     * PATCH /admin/users/{user_id}/roles
     */
    public function updateRoles(AssignRolesRequest $request, int $user_id): JsonResponse
    {
        $dto = AssignRolesDto::fromArray($request->validated(), $user_id);
        $roles = $this->userService->assignRoles($dto);
        return $this->success($roles);
    }
}
