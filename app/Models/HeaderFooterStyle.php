<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeaderFooterStyle extends Model
{
    protected $fillable = [
        'theme_id',
        'type',
        'name',
        'slug',
        'is_active',
        'preview_image',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(InstalledTheme::class, 'theme_id');
    }
}
