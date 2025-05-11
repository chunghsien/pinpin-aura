<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    /**
     * 可批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'theme_id',
        'language_locale_id',
        'index',
        'name',
        'route',
        'sort',
        'is_allowed_methods',
        'is_active',
    ];

    /**
     * 應該被轉換為原生類型的屬性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'theme_id' => 'integer',
        'language_locale_id' => 'integer',
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * 獲取文件所屬的主題
     */
    public function theme()
    {
        return $this->belongsTo(InstalledTheme::class, 'theme_id');
    }

    /**
     * 獲取文件所屬的語言地區
     */
    public function languageLocale()
    {
        return $this->belongsTo(LanguageLocale::class, 'language_locale_id');
    }
}
