<?php

namespace App\Http\Resources;

class UserResource
{
    public static function transform($user): array
    {
        $data = [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'phone' => $user->phone,
            'is_admin' => $user->is_admin,
            'status' => $user->status,
        ];

        // Include profile if loaded
        if ($user->relationLoaded('profile') && $user->profile) {
            $data['first_name'] = $user->profile->first_name;
            $data['middle_name'] = $user->profile->middle_name;
            $data['last_name'] = $user->profile->last_name;
            $data['avatar'] = $user->profile->avatar;
            $data['profile'] = $user->profile->profile;
            $data['registered_at'] = $user->registered_at?->toIso8601String();
            $data['last_login'] = $user->last_login?->toIso8601String();
        }

        return $data;
    }

    public static function collection($users): array
    {
        return $users->map(fn($user) => self::transform($user))->toArray();
    }
}
