<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopCartController extends Controller
{
    public function query()
    {
        return view('shop.cart', [
            'layout' => $this->getLayout(),
        ]);
    }
}
