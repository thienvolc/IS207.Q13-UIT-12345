<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
