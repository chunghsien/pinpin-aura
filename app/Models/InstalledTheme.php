<?php

namespace App\Models;

use App\Models\Traits\Activate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstalledTheme extends Model
{
    use HasFactory, Activate;

    protected $table = 'installed_themes';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'source_type',
        'version',
        'author',
        'description',
        'is_active',
        'installed_at',
    ];

    /**
     * @var list<string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'installed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pageStyles()
    {
        return $this->hasMany(PageStyle::class);
    }

    public function themeComponents()
    {
        return $this->hasMany(ThemeComponent::class);
    }
}
