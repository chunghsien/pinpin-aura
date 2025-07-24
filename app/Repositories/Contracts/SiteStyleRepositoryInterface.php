<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SiteStyleRepositoryInterface
{
    public function getByUseType(string $useType): ?\App\Models\SiteStyle;

    public function all(): Collection;
}
