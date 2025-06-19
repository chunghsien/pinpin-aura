<?php

namespace App\Http\Controllers;

use App\Services\LayoutService;
use Illuminate\Http\Request;

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
