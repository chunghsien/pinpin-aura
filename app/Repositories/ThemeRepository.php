<?php

namespace App\Repositories;

use App\Models\InstalledTheme;

class ThemeRepository implements ThemeRepositoryInterface
{
    public function getActiveSiteTheme()
    {
        return InstalledTheme::where('use_type', 'site')
            ->where('is_active', 1)
            ->first();
    }
}
