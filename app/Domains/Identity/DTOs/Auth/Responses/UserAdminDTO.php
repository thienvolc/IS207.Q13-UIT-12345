<?php

namespace App\Domains\Identity\DTOs\Auth\Responses;

class UserAdminDTO
{
    public static function transform($user): array
    {
        $data = [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'phone' => $user->phone,
            'is_admin' => $user->is_admin,
            'status' => $user->status,
            'created_at' => $user->created_at?->toIso8601String(),
            'updated_at' => $user->updated_at?->toIso8601String(),
        ];

        if ($user->relationLoaded('profile') && $user->profile) {
            $data['first_name'] = $user->profile->first_name;
            $data['middle_name'] = $user->profile->middle_name;
            $data['last_name'] = $user->profile->last_name;
            $data['avatar'] = $user->profile->avatar;
            $data['profile'] = $user->profile->profile;
            $data['registered_at'] = $user->registered_at?->toIso8601String();
            $data['last_login'] = $user->last_login?->toIso8601String();

            $data['profiles'] = [[
                'first_name' => $user->profile->first_name,
                'middle_name' => $user->profile->middle_name,
                'last_name' => $user->profile->last_name,
                'avatar' => $user->profile->avatar,
                'profile' => $user->profile->profile,
                'registered_at' => $user->registered_at?->toIso8601String(),
                'last_login' => $user->last_login?->toIso8601String(),
            ]];
        }

        if ($user->relationLoaded('roles')) {
            $data['roles'] = $user->roles->map(function ($role) {
                return [
                    'role_id' => $role->role_id,
                    'name' => $role->name,
                ];
            })->toArray();
        }

        return $data;
    }
}


