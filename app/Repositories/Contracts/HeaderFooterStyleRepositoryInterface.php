<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\HeaderFooterStyle;

interface HeaderFooterStyleRepositoryInterface
{
    public function getHeaderByThemeId(int $themeId): HeaderFooterStyle;
}
