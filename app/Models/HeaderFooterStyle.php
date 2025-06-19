<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeaderFooterStyle extends Model
{
    use HasFactory;

    protected $table = 'header_footer_styles';

    protected $fillable = [
        'theme_id',
        'properties',
        'type',
        'name',
        'slug',
        'is_active',
        'preview_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'properties' => 'array',
    ];

    /**
     * 類型常數
     */
    public const TYPE_HEADER = 'header';
    public const TYPE_FOOTER = 'footer';

    /**
     * 關聯主題
     */
    public function theme()
    {
        return $this->belongsTo(InstalledTheme::class, 'theme_id');
    }

    /**
     * 作用中樣式 Scope
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 依類型篩選 Scope
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
