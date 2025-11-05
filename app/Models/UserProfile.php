<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $primaryKey = 'user_id';
    protected $table = 'user_profiles';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'first_name', 'middle_name', 'last_name',
        'avatar', 'profile',
        'registered_at', 'last_login',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'last_login'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

