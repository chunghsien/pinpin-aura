<?php

namespace App\Models;

use App\Models\Traits\Activate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageStyle extends Model
{
    use HasFactory, Activate;

    /**
     *
     * @var list<string>
     */
    protected $fillable = [
        'installed_theme_id',
        'name',
        'properties',
        'is_active',
    ];

    /**
     *
     * @var list<string>
     */
    protected $casts = [
        'installed_theme_id' => 'integer',
        'is_active' => 'boolean',
        'properties' => 'array', // 假設這是 JSON 字串
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function installedTheme()
    {
        return $this->belongsTo(InstalledTheme::class);
    }
}
