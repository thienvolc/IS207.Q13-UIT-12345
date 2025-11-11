<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Role Model
 *
 * @property int $role_id Primary key
 * @property string $name Role name
 * @property string|null $desc Role description
 * @property int|null $created_by User who created this role
 * @property int|null $updated_by User who updated this role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users Users with this role
 * @property-read int|null $users_count
 */
class Role extends Model
{
    protected $primaryKey = 'role_id';
    protected $table = 'roles';

    protected $fillable = [
        'name', 'desc',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'user_id', 'role_id');
    }
}
