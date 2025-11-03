<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    // Khóa chính là 'user_id', không phải 'id'
    protected $primaryKey = 'user_id';
    
    // Khóa chính không tự tăng
    public $incrementing = false;

    // Bảng này cũng không dùng timestamps (created_at, updated_at)
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'avatar',
        'profile',
        'registered_at',
        'last_login',
    ];

    /**
     * Lấy user sở hữu profile này.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}