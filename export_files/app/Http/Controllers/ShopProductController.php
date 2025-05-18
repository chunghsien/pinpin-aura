<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    public function query()
    {
        return view('shop.product', [
            'layout' => $this->getLayout(),
        ]);
    }
}
