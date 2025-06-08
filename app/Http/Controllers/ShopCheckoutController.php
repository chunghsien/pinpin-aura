<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class ShopCheckoutController extends Controller
{
    public function query()
    {
        return view(
            'shop.checkout',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
