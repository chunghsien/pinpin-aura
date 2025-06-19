<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

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
