<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class IndexController extends Controller
{
    public function query()
    {
        return view(
            'index',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
