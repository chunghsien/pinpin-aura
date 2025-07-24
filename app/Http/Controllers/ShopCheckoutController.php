<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

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
