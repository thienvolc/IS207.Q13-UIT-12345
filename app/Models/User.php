<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserProfile;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $table = 'users';

    protected $fillable = [
        'email',
        'phone',
        'password',
        'salt',
        'is_admin',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'salt',
    ];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'status' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
