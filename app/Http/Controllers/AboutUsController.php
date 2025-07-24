<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class AboutUsController extends Controller
{
    public function query()
    {
        return view(
            'about-us',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
