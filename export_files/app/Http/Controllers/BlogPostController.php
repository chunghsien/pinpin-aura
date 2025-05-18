<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function query()
    {
        return view('blog.post', [
            'layout' => $this->getLayout(),
        ]);
    }
}
