<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function query()
    {
        return view(
            'blog.category',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
