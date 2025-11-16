<?php

namespace App\Domains\Identity\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * UserProfile Model
 *
 * @property int $user_id Primary key and foreign key to users
 * @property string|null $first_name User first name
 * @property string|null $middle_name User middle name
 * @property string|null $last_name User last name
 * @property string|null $avatar Avatar image path
 * @property string|null $profile Profile description
 * @property Carbon|null $registered_at Registration timestamp
 * @property Carbon|null $last_login Last login timestamp
 *
 * @property-read User|null $user Associated user
 * @method static updateOrCreate(int[] $array, array $data)
 * @method static create(array $data)
 */
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
        'last_login' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
