<?php

namespace App\Repositories;

use App\Models\InstalledTheme;

class ThemeRepository implements Contracts\ThemeRepositoryInterface
{
    public function getActiveSiteTheme($use_type = 'web')
    {
        return InstalledTheme::where('use_type', $use_type)
            ->where('is_active', 1)
            ->first();
    }
}
