<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locale extends Model
{
    use SoftDeletes;

    /**
     * 可批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    /**
     * 應該被轉換為原生類型的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 獲取地區的所有語言地區
     */
    public function languageLocales()
    {
        return $this->hasMany(LanguageLocale::class);
    }
}
