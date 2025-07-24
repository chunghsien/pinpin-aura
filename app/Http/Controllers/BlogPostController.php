<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LayoutService;

class BlogPostController extends Controller
{
    public function query()
    {
        return view(
            'blog.post',
            $this->getLayout()->merge(app(LayoutService::class)->getViewModel('web'))
        );
    }
}
