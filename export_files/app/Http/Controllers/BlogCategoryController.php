<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function query()
    {
        return view('blog.category', [
            'layout' => $this->getLayout(),
        ]);
    }
}
