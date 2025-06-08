<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    public function query()
    {
        return view(
            'shop.product',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
