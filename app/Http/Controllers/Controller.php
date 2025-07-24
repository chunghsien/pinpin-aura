<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ThemeService;
use Illuminate\Support\Collection;

/**
 * @desc 當作是site類型的基礎控制器(controller)
 */
abstract class Controller
{
    abstract public function query();

    public function post()
    {
        return abort(417, "如需使用請在繼承類別定義");
    }

    protected function getLayout(): Collection
    {
        $useType = 'web';

        return collect([
            'layout' => app(ThemeService::class)->getLayout($useType),
        ]);
    }
}
