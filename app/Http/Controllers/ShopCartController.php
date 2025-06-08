<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class ShopCartController extends Controller
{
    public function query()
    {
        return view(
            'shop.cart',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
