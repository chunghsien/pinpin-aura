<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteStyle extends Model
{
    protected $fillable = [
        'use_type',
        'header_style_id',
        'footer_style_id',
    ];

    public function headerStyle(): BelongsTo
    {
        return $this->belongsTo(HeaderFooterStyle::class, 'header_style_id');
    }

    public function footerStyle(): BelongsTo
    {
        return $this->belongsTo(HeaderFooterStyle::class, 'footer_style_id');
    }
}
