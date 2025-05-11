<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'key';


    public static function get(string $key, $default = null)
    {
        return optional(static::find($key))->value ?? $default;
    }

    public static function set(string $key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * 動態解析 value：JSON 字串 => 陣列，其餘回傳原值
     */
    public function getValueAttribute($value)
    {
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;
    }
}
