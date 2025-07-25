<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class MyAccountController extends Controller
{
    public function query()
    {
        /** @var LayoutService $layoutService */
        $layoutService = app(LayoutService::class);
        return view(
            'my-account',
            $this->getLayout()->merge($layoutService->getViewModel('web'))
        );
    }
}
