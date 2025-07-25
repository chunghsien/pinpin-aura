<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeComponent extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'installed_theme_id',
        'component_type',
        'resolve_name',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function installedTheme()
    {
        return $this->belongsTo(InstalledTheme::class);
    }
}
