<?php

namespace App\Repositories\Contracts;

use App\Models\HeaderFooterStyle;

interface HeaderFooterStyleRepositoryInterface
{
    public function getHeaderByThemeId(int $themeId): HeaderFooterStyle;
}
