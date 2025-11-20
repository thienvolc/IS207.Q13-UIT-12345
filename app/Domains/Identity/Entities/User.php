<?php

namespace App\Domains\Identity\Entities;

use App\Domains\Cart\Entities\Cart;
use App\Domains\Order\Entities\Order;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * User Model
 *
 * @property int $user_id Primary key
 * @property string $email User email address
 * @property string|null $phone User phone number
 * @property string $password Hashed password
 * @property string|null $salt Password salt
 * @property bool $is_admin Admin flag
 * @property int $status User status (active/inactive/banned)
 * @property Carbon|null $registered_at Registration timestamp
 * @property Carbon|null $last_login Last login timestamp
 * @property int|null $created_by User who created this record
 * @property int|null $updated_by User who updated this record
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read UserProfile|null $profile User profile information
 * @property-read Collection<int, Role> $roles User roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Cart> $carts User carts
 * @property-read int|null $carts_count
 * @property-read Collection<int, Order> $orders User orders
 * @property-read int|null $orders_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static create(array $data)
 * @method static where(string $string, string $email)
 */
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
