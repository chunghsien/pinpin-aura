<?php

namespace App\Repositories;

use App\Models\HeaderFooterStyle;
use App\Repositories\HeaderFooterStyleRepositoryInterface;

class HeaderFooterStyleRepository implements Contracts\HeaderFooterStyleRepositoryInterface
{
    public function getHeaderByThemeId(int $themeId): HeaderFooterStyle
    {
        return HeaderFooterStyle::where('theme_id', $themeId)
            ->where('type', 'header')
            ->firstOrFail();
    }
}
