<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserProfile; // <-- Thêm dòng này

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Báo cho Eloquent biết bảng này KHÔNG SỬ DỤNG
     * các cột 'created_at' và 'updated_at'.
     */
    public $timestamps = false; // <--- Sửa lỗi "Unknown column 'updated_at'"

    /**
     * Lấy profile của user.
     */
    public function profile()
    {
        // Quan hệ một-một: Một User có một UserProfile
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'phone',
        'password',
        'is_admin',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'salt', // Giấu salt (nếu có)
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}