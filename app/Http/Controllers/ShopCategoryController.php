<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    public function query()
    {
        return view('shop.category', []);
    }
}
