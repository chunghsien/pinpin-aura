<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
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
        'display_name',
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
     * 獲取語言的所有語言地區
     */
    public function languageLocales()
    {
        return $this->hasMany(LanguageLocale::class);
    }
}
