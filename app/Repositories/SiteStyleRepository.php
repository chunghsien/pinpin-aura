<?php

namespace App\Repositories;

use App\Models\SiteStyle;
use App\Repositories\Contracts\SiteStyleRepositoryInterface;
use Illuminate\Support\Collection;

class SiteStyleRepository implements SiteStyleRepositoryInterface
{
    public function getByUseType(string $useType): ?SiteStyle
    {
        return SiteStyle::where('use_type', $useType)->first();
    }

    public function all(): Collection
    {
        return SiteStyle::all();
    }
}
