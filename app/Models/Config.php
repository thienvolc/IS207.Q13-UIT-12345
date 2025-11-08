<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Config Model
 *
 * @property string $key Primary key - configuration key
 * @property string|null $value Configuration value
 * @property int $type Value type (string/int/bool/json)
 * @property string|null $desc Configuration description
 * @property \Illuminate\Support\Carbon|null $updated_at Last update timestamp
 */
class Config extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'configs';

    public $timestamps = true;
    public const CREATED_AT = null;
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'key', 'value', 'type', 'desc', 'updated_at',
    ];

    protected $casts = [
        'type'      => 'integer',
        'updated_at'=> 'datetime',
    ];
}
