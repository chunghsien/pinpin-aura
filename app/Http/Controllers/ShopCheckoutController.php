<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopCheckoutController extends Controller
{
    public function query()
    {
        return view('shop.checkout', $this->getLayout());
    }
}
