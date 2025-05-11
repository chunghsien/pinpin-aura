<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageLocale extends Model
{
    /**
     * 與模型關聯的資料表
     *
     * @var string
     */
    protected $table = 'language_locale';

    /**
     * 模型的主鍵
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 主鍵的資料類型
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * 指示主鍵是否自動遞增
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * 可批量賦值的屬性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id',
        'locale_id',
        'php_code',
        'html_code',
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
     * 獲取語言地區所屬的語言
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * 獲取語言地區所屬的地區
     */
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

    /**
     * 獲取語言地區的所有文件
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
