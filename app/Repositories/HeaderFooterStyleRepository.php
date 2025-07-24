<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\HeaderFooterStyle;

class HeaderFooterStyleRepository implements Contracts\HeaderFooterStyleRepositoryInterface
{
    public function getHeaderByThemeId(int $themeId): HeaderFooterStyle
    {
        return HeaderFooterStyle::where('theme_id', $themeId)
            ->where('type', 'header')
            ->firstOrFail();
    }
}
