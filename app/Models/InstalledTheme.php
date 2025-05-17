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
        'use_type',
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

    /**
     * @see parent::activate()
     * @desc 因為還要靠use_type欄位決定所以override寫特例
     */
    public function activate(string $useType): void
    {
        self::where('id', '!=', $this->id)->where('use_type', '=', $useType)
            ->update(['is_active' => false]);
        $this->update(['is_active' => true]);
        // 啟用這個主題（如果尚未啟用）
        if (!$this->is_active) {
            $this->is_active = true;
            $this->save();
        }
    }
}
