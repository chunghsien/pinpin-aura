<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class CustomController extends Controller
{
    public function query()
    {
        /** @var LayoutService $layoutService */
        $layoutService = app(LayoutService::class);
        return view(
            'custom-page',
            $this->getLayout()->merge($layoutService->getViewModel('web'))
        );
    }
}
