<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class AboutUsController extends Controller
{
    public function query()
    {
        /** @var LayoutService $layoutService */
        $layoutService = app(LayoutService::class);
        return view(
            'about-us',
            $this->getLayout()->merge($layoutService->getViewModel('web'))
        );
    }
}
