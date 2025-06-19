<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    public function query()
    {
        return view(
            'shop.category',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
