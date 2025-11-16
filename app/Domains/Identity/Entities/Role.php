<?php

namespace App\Domains\Identity\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Role Model
 *
 * @property int $role_id Primary key
 * @property string $name Role name
 * @property string|null $desc Role description
 * @property int|null $created_by User who created this role
 * @property int|null $updated_by User who updated this role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection<int, User> $users Users with this role
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
