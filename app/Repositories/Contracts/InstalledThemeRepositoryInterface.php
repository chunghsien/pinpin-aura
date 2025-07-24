<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\InstalledTheme;

interface InstalledThemeRepositoryInterface
{
    public function getActiveThemeByType(string $useType): InstalledTheme;
}
