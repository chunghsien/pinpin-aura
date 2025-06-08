<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

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
