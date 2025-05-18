<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopWishlistController extends Controller
{
    public function query()
    {
        return view('shop.wishlist', [
            'layout' => $this->getLayout(),
        ]);
    }
}
