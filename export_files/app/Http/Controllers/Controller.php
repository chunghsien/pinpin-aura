<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;

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

    protected function getLayout()
    {
        return app(ThemeService::class)->getLayout();
    }
}
