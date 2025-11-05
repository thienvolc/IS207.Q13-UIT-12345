<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';
    protected $fillable = ['token', 'user_id', 'is_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
