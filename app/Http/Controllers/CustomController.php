<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;

class CustomController extends Controller
{
    public function query()
    {
        return view(
            'custom-page',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
