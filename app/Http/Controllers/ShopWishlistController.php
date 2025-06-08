<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class ShopWishlistController extends Controller
{
    public function query()
    {
        return view(
            'shop.wishlist',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
