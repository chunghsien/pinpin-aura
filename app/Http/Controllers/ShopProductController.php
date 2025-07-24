<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

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
