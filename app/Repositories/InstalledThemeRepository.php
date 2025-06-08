<?php

namespace App\Repositories;

use App\Models\InstalledTheme;

class InstalledThemeRepository implements Contracts\InstalledThemeRepositoryInterface
{
    public function getActiveThemeByType(string $useType): InstalledTheme
    {
        return InstalledTheme::where('use_type', $useType)
            ->where('is_active', 1)
            ->firstOrFail();
    }
}
