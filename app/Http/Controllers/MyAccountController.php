<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class MyAccountController extends Controller
{
    public function query()
    {
        return view(
            'my-account',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
