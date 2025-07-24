<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

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
